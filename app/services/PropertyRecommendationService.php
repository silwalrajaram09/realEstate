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
        'price' => 30,
        'type' => 20,
        'purpose' => 15,
        'bedrooms' => 10,
        'bathrooms' => 5,
        'text' => 15,
        'location' => 5,
    ];

    private const TOLERANCE = [
        'price' => 0.20,
        'bedrooms' => 1,
        'bathrooms' => 1,
    ];

    private const EARTH_RADIUS = 6371; // km
    private const LOCATION_RADIUS = 10; // km, smaller to avoid other cities

    // public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    // {
    //     $query = Property::query()->approved();

    //     $this->applyHardFilters($query, $filters);
    //     $this->applyScoring($query, $filters);

    //     return $query
    //         ->orderByDesc('relevance_score')
    //         ->paginate(min(max($perPage, 6), 48));
    // }
    public function search(array $filters, int $perPage = 6): LengthAwarePaginator
    {
        $query = Property::query()->approved();

        $this->applyHardFilters($query, $filters);
        $this->applyScoring($query, $filters);

        // Sorting logic
        $sort = $filters['sort'] ?? null;

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'latest') {
            $query->orderBy('created_at', 'desc');
        } else {
            // Default: AI relevance
            $query->orderByDesc('relevance_score');
        }

        return $query
            ->paginate(min(max($perPage, 6), 48))
            ->withQueryString();
    }


    public function personalized(array $preferences, int $limit = 10): Collection
    {
        $query = Property::query()->approved();

        $this->applyScoring($query, $preferences);

        return $query
            ->orderByDesc('relevance_score')
            ->limit($limit)
            ->get();
    }

    // Similar properties with radius + city filter
    public function getSimilarProperties(Property $property, int $limit = 6): Collection
    {
        // $city = trim(explode(',', $property->location)[1] ?? '');
        return Property::query()
            ->approved()
            ->where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->whereBetween('price', [$property->price * 0.8, $property->price * 1.2])
            // ->when($city, fn($q) => $q->where('location', 'LIKE', "%{$city}%"))// ensures same city
            ->whereNotNull(['latitude', 'longitude'])
            ->selectRaw("*, (
                ? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [self::EARTH_RADIUS, $property->latitude, $property->longitude, $property->latitude])
            ->having('distance', '<=', self::LOCATION_RADIUS)
            ->orderBy('distance')
            ->limit($limit)
            ->get();
    }

    public function withinRadius(float $lat, float $lng, int $radius = 10): Collection
    {
        return Property::query()
            ->approved()
            ->whereNotNull(['latitude', 'longitude'])
            ->selectRaw("properties.*, (
                ? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [self::EARTH_RADIUS, $lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    private function applyHardFilters(Builder $query, array $data): void
    {
        foreach (['purpose', 'type', 'category'] as $field) {
            if (!empty($data[$field])) {
                $query->where($field, $data[$field]);
            }
        }
    }

    private function applyScoring(Builder $query, array $data): void
    {
        $parts = [];
        $bindings = [];

        $this->priceScore($data, $parts, $bindings);
        $this->exactMatchScore('type', $data, self::WEIGHTS['type'], $parts, $bindings);
        $this->exactMatchScore('purpose', $data, self::WEIGHTS['purpose'], $parts, $bindings);
        $this->rangeScore('bedrooms', $data, self::WEIGHTS['bedrooms'], self::TOLERANCE['bedrooms'], $parts, $bindings);
        $this->rangeScore('bathrooms', $data, self::WEIGHTS['bathrooms'], self::TOLERANCE['bathrooms'], $parts, $bindings);
        $this->textScore($data, $parts, $bindings);
        $this->locationScore($data, $parts, $bindings);

        // fallback if no scoring
        if (empty($parts)) {
            $query->selectRaw('properties.*, 0 AS relevance_score');
        } else {
            $query->selectRaw('properties.*, (' . implode(' + ', $parts) . ') AS relevance_score', $bindings);
        }
    }

    // -------------------- SCORING --------------------
    private function priceScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['min_price']) || empty($data['max_price']))
            return;
        $target = ($data['min_price'] + $data['max_price']) / 2;
        $tolerance = $target * self::TOLERANCE['price'];
        $parts[] = "CASE WHEN ABS(price - ?) <= ? THEN ? WHEN ABS(price - ?) <= ? * 2 THEN ? * 0.5 ELSE 0 END";
        array_push($bindings, $target, $tolerance, self::WEIGHTS['price'], $target, $tolerance, self::WEIGHTS['price']);
    }

    //using min and max price separately to allow more flexible scoring
    // private function priceScore(array $data, array &$parts, array &$bindings): void
    // {
    //     if (empty($data['min_price']) || empty($data['max_price'])) {
    //         return;
    //     }

    //     // Calculate tolerance separately for min and max
    //     $minTolerance = $data['min_price'] * self::TOLERANCE['price'];
    //     $maxTolerance = $data['max_price'] * self::TOLERANCE['price'];

    //     $rangeMin = $data['min_price'] - $minTolerance;
    //     $rangeMax = $data['max_price'] + $maxTolerance;

    //     // Full points if inside the range, half points if slightly outside
    //     $parts[] = "
    //     CASE
    //         WHEN price BETWEEN ? AND ? THEN ?
    //         WHEN price BETWEEN ? AND ? THEN ? * 0.5
    //         ELSE 0
    //     END
    // ";

    //     array_push(
    //         $bindings,
    //         $rangeMin,
    //         $rangeMax,
    //         self::WEIGHTS['price'],           // full points
    //         $rangeMin - $minTolerance,        // slightly below
    //         $rangeMax + $maxTolerance,        // slightly above
    //         self::WEIGHTS['price']            // half points
    //     );
    // }


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
        $parts[] = "CASE WHEN ABS({$field} - ?) <= ? THEN ? WHEN ABS({$field} - ?) <= ? * 2 THEN ? * 0.5 ELSE 0 END";
        array_push($bindings, $data[$field], $tolerance, $weight, $data[$field], $tolerance, $weight);
    }

    private function textScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['q']))
            return;
        $like = '%' . $data['q'] . '%';
        $parts[] = "( (CASE WHEN title LIKE ? THEN 3 ELSE 0 END) + (CASE WHEN location LIKE ? THEN 2 ELSE 0 END) + (CASE WHEN description LIKE ? THEN 1 ELSE 0 END) )";
        array_push($bindings, $like, $like, $like);
    }

    private function locationScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['lat']) || empty($data['lng']))
            return;
        $parts[] = "CASE WHEN latitude IS NOT NULL AND longitude IS NOT NULL THEN GREATEST(0, ? * (1 - ((? * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) / ?))) ELSE 0 END";
        array_push($bindings, self::WEIGHTS['location'], self::EARTH_RADIUS, $data['lat'], $data['lng'], $data['lat'], self::LOCATION_RADIUS);
    }

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
