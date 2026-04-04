<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PropertyRecommendationService
{
    private const WEIGHTS = [
        'price'     => 25,
        'type'      => 10,
        'purpose'   => 10,
        'category'  => 10,
        'bedrooms'  => 5,
        'bathrooms' => 5,
        'text'      => 10,  // now powered by cosine instead of raw LIKE score
        'location'  => 25,
    ];

    private const TOLERANCE = [
        'price'     => 0.20,
        'bedrooms'  => 1,
        'bathrooms' => 1,
    ];

    private const EARTH_RADIUS    = 6371; // km
    private const LOCATION_RADIUS = 50;   // km

    public function __construct(
        protected Cosinesimilarityservice $cosine
    ) {}

    
    public function search(array $filters, int $perPage = 6): LengthAwarePaginator
    {
        $query = Property::query()
            ->approved()
            ->with('seller');

        $this->applyFilters($query, $filters);
        $this->applyScoring($query, $filters);
        $this->applySorting($query, $filters);

        $paginator = $query
            ->paginate(min(max($perPage, 6), 48))
            ->withQueryString();

        // If there's a text query, blend cosine score into relevance_score
        if (!empty($filters['q'])) {
            $paginator->getCollection()->transform(function ($property) use ($filters) {
                $cosineScore = $this->cosine->score($property, $filters['q']);

                // Blend: DB relevance_score (0–100 range) + cosine (0–1) × text weight
                $property->relevance_score = ($property->relevance_score ?? 0)
                    + ($cosineScore * self::WEIGHTS['text']);

                $property->cosine_score = round($cosineScore, 4);
                return $property;
            });

            // Re-sort the current page by final blended score
            $sorted = $paginator->getCollection()->sortByDesc('relevance_score')->values();
            $paginator->setCollection($sorted);
        }

        return $paginator;
    }

   
    public function personalized(array $preferences, int $limit = 10): Collection
    {
        $query = Property::query()
            ->approved()
            ->with('seller');

        $this->applyFilters($query, $preferences);
        $this->applyScoring($query, $preferences);

        $results = $query
            ->orderByDesc('relevance_score')
            ->orderBy('price', 'asc')
            ->limit($limit * 3) // fetch wider pool for cosine re-ranking
            ->get();

        // Blend cosine score if there's a text query in preferences
        if (!empty($preferences['q'])) {
            $results = $this->cosine->rerank($results, $preferences['q'])
                ->map(function ($property) use ($preferences) {
                    $cosineScore = $property->cosine_score ?? 0;
                    $property->relevance_score = ($property->relevance_score ?? 0)
                        + ($cosineScore * self::WEIGHTS['text']);
                    return $property;
                })
                ->sortByDesc('relevance_score')
                ->values();
        }

        return $results->take($limit);
    }

    /*
    |--------------------------------------------------------------------------
    | Similar Properties
    |--------------------------------------------------------------------------
    */
    public function getSimilarProperties(Property $property, int $limit = 6): Collection
    {
        $query = Property::query()
            ->approved()
            ->with('seller')
            ->where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->whereBetween('price', [
                $property->price * 0.8,
                $property->price * 1.2
            ]);

        if ($property->latitude && $property->longitude) {
            $this->applyBoundingBox($query, $property->latitude, $property->longitude);

            $query->selectRaw("properties.*, (
                ? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [
                self::EARTH_RADIUS,
                $property->latitude,
                $property->longitude,
                $property->latitude
            ])
                ->having('distance', '<=', self::LOCATION_RADIUS)
                ->orderBy('distance');
        }

        $candidates = $query->limit($limit * 3)->get();

        // Re-rank similar properties by cosine similarity to the source property's text
        $referenceText = implode(' ', [
            $property->title ?? '',
            $property->location ?? '',
            $property->description ?? '',
        ]);

        if (trim($referenceText) !== '') {
            return $this->cosine->rerank($candidates, $referenceText)->take($limit);
        }

        return $candidates->take($limit);
    }

    /*
    |--------------------------------------------------------------------------
    | Radius Search
    |--------------------------------------------------------------------------
    */
    public function withinRadius(float $lat, float $lng, int $radius = 10): Collection
    {
        $query = Property::query()
            ->approved()
            ->whereNotNull(['latitude', 'longitude']);

        $this->applyBoundingBox($query, $lat, $lng, $radius);

        return $query
            ->selectRaw("properties.*, (
                ? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [
                self::EARTH_RADIUS,
                $lat,
                $lng,
                $lat
            ])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Filtering (Uses Model Scopes)
    |--------------------------------------------------------------------------
    */
    private function applyFilters(Builder $query, array $data): void
    {
        $query
            ->purpose($data['purpose'] ?? null)
            ->type($data['type'] ?? null)
            ->category($data['category'] ?? null)
            ->priceRange($data['min_price'] ?? null, $data['max_price'] ?? null)
            ->minBedrooms($data['bedrooms'] ?? null)
            ->minBathrooms($data['bathrooms'] ?? null)
            ->search($data['q'] ?? null);

        if (!empty($data['lat']) && !empty($data['lng'])) {
            $this->applyBoundingBox($query, $data['lat'], $data['lng']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Scoring System
    |--------------------------------------------------------------------------
    */
    private function applyScoring(Builder $query, array $data): void
    {
        $parts    = [];
        $bindings = [];

        $this->priceScore($data, $parts, $bindings);
        $this->exactMatchScore('type', $data, self::WEIGHTS['type'], $parts, $bindings);
        $this->exactMatchScore('purpose', $data, self::WEIGHTS['purpose'], $parts, $bindings);
        $this->exactMatchScore('category', $data, self::WEIGHTS['category'], $parts, $bindings);
        $this->rangeScore('bedrooms', $data, self::WEIGHTS['bedrooms'], self::TOLERANCE['bedrooms'], $parts, $bindings);
        $this->rangeScore('bathrooms', $data, self::WEIGHTS['bathrooms'], self::TOLERANCE['bathrooms'], $parts, $bindings);
        $this->locationScore($data, $parts, $bindings);

        // NOTE: text weight is intentionally excluded from SQL scoring here.
        // Cosine similarity is added as a PHP post-processing step in search() / personalized()
        // to keep SQL clean and benefit from TF-IDF corpus-aware weighting.

        if (empty($parts)) {
            $query->selectRaw('properties.*, 0 AS relevance_score');
        } else {
            $query->selectRaw(
                'properties.*, (' . implode(' + ', $parts) . ') AS relevance_score',
                $bindings
            );
        }
    }

    private function priceScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['min_price']) && empty($data['max_price']))
            return;

        $min       = $data['min_price'] ?? 0;
        $max       = $data['max_price'] ?? 999999999;
        $target    = ($min + $max) / 2;
        $tolerance = $target * self::TOLERANCE['price'];

        $parts[] = "
            CASE
                WHEN price BETWEEN ? AND ? THEN ?
                WHEN price BETWEEN ? AND ? THEN ? * 0.5
                ELSE 0
            END
        ";

        array_push($bindings, $min, $max, self::WEIGHTS['price'], $min - $tolerance, $max + $tolerance, self::WEIGHTS['price']);
    }

    private function exactMatchScore(string $field, array $data, int $weight, array &$parts, array &$bindings): void
    {
        if (empty($data[$field]))
            return;

        $parts[] = "CASE WHEN {$field} = ? THEN ? ELSE 0 END";
        array_push($bindings, $data[$field], $weight);
    }

    private function rangeScore(string $field, array $data, int $weight, int $tolerance, array &$parts, array &$bindings): void
    {
        if (empty($data[$field]))
            return;

        $parts[] = "
            CASE 
                WHEN ABS({$field} - ?) <= ? THEN ?
                WHEN ABS({$field} - ?) <= ? * 2 THEN ? * 0.5
                ELSE 0
            END
        ";

        array_push($bindings, $data[$field], $tolerance, $weight, $data[$field], $tolerance, $weight);
    }

    private function locationScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['lat']) || empty($data['lng']))
            return;

        $parts[] = "
            GREATEST(0,
                ? * (
                    1 - (
                        ? * acos(
                            cos(radians(?)) * cos(radians(latitude)) *
                            cos(radians(longitude) - radians(?)) +
                            sin(radians(?)) * sin(radians(latitude))
                        )
                    ) / ?
                )
            )
        ";

        array_push($bindings, self::WEIGHTS['location'], self::EARTH_RADIUS, $data['lat'], $data['lng'], $data['lat'], self::LOCATION_RADIUS);
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */
    private function applySorting(Builder $query, array $filters): void
    {
        $sort = $filters['sort'] ?? null;

        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'latest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderByDesc('relevance_score')->orderBy('price', 'asc'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Bounding Box Optimization
    |--------------------------------------------------------------------------
    */
    private function applyBoundingBox(Builder $query, float $lat, float $lng, int $radius = self::LOCATION_RADIUS): void
    {
        $latDelta = $radius / 111;
        $lngDelta = $radius / (111 * cos(deg2rad($lat)));

        $query->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);
    }

    /*
    |--------------------------------------------------------------------------
    | Stats (Cached)
    |--------------------------------------------------------------------------
    */
    public function stats(): array
    {
        return Cache::remember('property_stats', 3600, fn() => [
            'total' => Property::approved()->count(),
            'sale'  => Property::approved()->where('purpose', 'buy')->count(),
            'rent'  => Property::approved()->where('purpose', 'rent')->count(),
            'avg'   => Property::approved()->avg('price'),
        ]);
    }
}