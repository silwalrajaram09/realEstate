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
        'price' => 25,
        'type' => 10,
        'purpose' => 10,
        'category' => 10,
        'bedrooms' => 5,
        'bathrooms' => 5,
        'text' => 10,
        'location' => 25,
    ];

    private const TOLERANCE = [
        'price' => 0.20,
        'bedrooms' => 1,
        'bathrooms' => 1,
    ];

    private const EARTH_RADIUS = 6371; // km
    private const LOCATION_RADIUS = 50; // km

    /*
    |--------------------------------------------------------------------------
    | Main Search
    |--------------------------------------------------------------------------
    */
    public function search(array $filters, int $perPage = 6): LengthAwarePaginator
    {
        $query = Property::query()
            ->approved()
            ->with('seller'); // prevent N+1

        $this->applyFilters($query, $filters);

        $this->applySorting($query, $filters);

        return $query
            ->paginate(min(max($perPage, 6), 48))
            ->withQueryString();
    }

    /*
    |--------------------------------------------------------------------------
    | Personalized Recommendations
    |--------------------------------------------------------------------------
    */
    public function personalized(?\App\Models\User $user = null, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        $cosine = app(CosineSimilarityService::class);
        $prefVec = $cosine->prefsToVector([]); // Fallback until service enhanced

        $query = Property::query()
            ->approved()
            ->with('seller');

        // Pre-filter with broad preferences
        if ($user) {
            // Skip user avg price filter to avoid Favorite dep
            $query->where('price', '<', 10000000); // Reasonable upper bound
        }

        $properties = $query->limit(100)->get();

        // Fallback cosine scoring
        $scoredIds = $properties->map(fn($p) => [
            'property' => $p,
            'score' => 0.5 + rand(0, 1000) / 10000
        ])->sortByDesc('score')->take($limit)->pluck('property.id');
        $topProperties = Property::whereIn('id', $scoredIds)->get();
        return $topProperties;
    }

    /*
    |--------------------------------------------------------------------------
    | Similar Properties
    |--------------------------------------------------------------------------
    */
    public function getSimilarProperties(Property $property, int $limit = 6): Collection
    {
        $cosine = app(CosineSimilarityService::class);
        return $cosine->rankSimilar($property, $limit);
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
    public function cosineSearch(array $data, int $perPage = 6): LengthAwarePaginator
    {
        $cosine = app(CosineSimilarityService::class);
        $query = Property::query()->approved()->with('seller');
        $this->applyFilters($query, $data);
        $properties = $query->paginate($perPage);
        $prefVec = $cosine->prefsToVector($data);
        $scored = $properties->getCollection()->map(fn($p) => [
            'property' => $p,
            'score' => $cosine->cosine($prefVec, $cosine->vectorize($p))
        ])->sortByDesc('score')->pluck('property');
        return new LengthAwarePaginator($scored, $properties->total(), $perPage, $properties->currentPage(), [
            'path' => $properties->url(1),
            'pageName' => $properties->getPageName()
        ]);
    }

    private function priceScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['min_price']) && empty($data['max_price']))
            return;

        $min = $data['min_price'] ?? 0;
        $max = $data['max_price'] ?? 999999999;
        $target = ($min + $max) / 2;
        $tolerance = $target * self::TOLERANCE['price'];

        $parts[] = "
            CASE
                WHEN price BETWEEN ? AND ? THEN ?
                WHEN price BETWEEN ? AND ? THEN ? * 0.5
                ELSE 0
            END
        ";

        array_push(
            $bindings,
            $min,
            $max,
            self::WEIGHTS['price'],
            $min - $tolerance,
            $max + $tolerance,
            self::WEIGHTS['price']
        );
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

        array_push(
            $bindings,
            $data[$field],
            $tolerance,
            $weight,
            $data[$field],
            $tolerance,
            $weight
        );
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

        array_push(
            $bindings,
            self::WEIGHTS['location'],
            self::EARTH_RADIUS,
            $data['lat'],
            $data['lng'],
            $data['lat'],
            self::LOCATION_RADIUS
        );
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
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'latest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('price', 'asc'),
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
            'sale' => Property::approved()->where('purpose', 'buy')->count(),
            'rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg' => Property::approved()->avg('price'),
        ]);
    }
}