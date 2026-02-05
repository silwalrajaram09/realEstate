<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class PropertyRecommendationService
{
    /** -------------------- CONFIG -------------------- */

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
    private const LOCATION_RADIUS = 50; // km

    /** -------------------- PUBLIC API -------------------- */

    public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Property::query()->approved();

        $this->applyHardFilters($query, $filters);
        $this->applyScoring($query, $filters);

        return $query
            ->orderByDesc('relevance_score')
            ->paginate(min(max($perPage, 6), 48));
    }

    public function personalized(array $preferences, int $limit = 10)
    {
        $query = Property::query()->approved();

        $this->applyScoring($query, $preferences);

        return $query
            ->orderByDesc('relevance_score')
            ->limit($limit)
            ->get();
    }

    /**
     * Get similar properties based on type and price range.
     */
    public function getSimilarProperties(Property $property, int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return Property::query()
            ->approved()
            ->where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->whereBetween('price', [
                $property->price * 0.7,
                $property->price * 1.3
            ])
            ->limit($limit)
            ->get();
    }

    /**
     * Get properties within a radius of given coordinates.
     */
    public function withinRadius(float $lat, float $lng, int $radius = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Property::query()
            ->approved()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, (
                ? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance", [self::EARTH_RADIUS, $lat, $lng, $lat])
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();
    }

    /** -------------------- CORE LOGIC -------------------- */

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

        $scoreSql = implode(' + ', $parts);

        $query->selectRaw("properties.*, ($scoreSql) as relevance_score", $bindings);
    }

    /** -------------------- SCORE BUILDERS -------------------- */

    private function priceScore(array $data, array &$parts, array &$bindings): void
    {
        if (!empty($data['min_price']) && !empty($data['max_price'])) {
            $target = ($data['min_price'] + $data['max_price']) / 2;
            $tolerance = $target * self::TOLERANCE['price'];

            $parts[] = "CASE
                WHEN ABS(price - ?) <= ? THEN ?
                WHEN ABS(price - ?) <= ? * 2 THEN ? * 0.5
                ELSE 0 END";

            array_push($bindings, $target, $tolerance, self::WEIGHTS['price'], $target, $tolerance, self::WEIGHTS['price']);
            return;
        }

        $parts[] = (string) self::WEIGHTS['price'];
    }

    private function exactMatchScore(string $field, array $data, int $weight, array &$parts, array &$bindings): void
    {
        if (!empty($data[$field])) {
            $parts[] = "CASE WHEN $field = ? THEN ? ELSE 0 END";
            array_push($bindings, $data[$field], $weight);
        } else {
            $parts[] = (string) $weight;
        }
    }

    private function rangeScore(string $field, array $data, int $weight, int $tolerance, array &$parts, array &$bindings): void
    {
        if (!empty($data[$field])) {
            $parts[] = "CASE
                WHEN $field BETWEEN ? AND ? THEN ?
                WHEN $field BETWEEN ? AND ? THEN ? * 0.5
                ELSE 0 END";

            array_push(
                $bindings,
                $data[$field],
                $data[$field] + $tolerance,
                $weight,
                $data[$field] - $tolerance - 1,
                $data[$field] + $tolerance + 1,
                $weight
            );
        } else {
            $parts[] = (string) $weight;
        }
    }

    private function textScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['q'])) {
            $parts[] = (string) self::WEIGHTS['text'];
            return;
        }

        $parts[] = "(
            (CASE WHEN title LIKE ? THEN 3 ELSE 0 END) +
            (CASE WHEN location LIKE ? THEN 2 ELSE 0 END) +
            (CASE WHEN description LIKE ? THEN 1 ELSE 0 END)
        )";

        $like = '%' . $data['q'] . '%';
        array_push($bindings, $like, $like, $like);
    }

    private function locationScore(array $data, array &$parts, array &$bindings): void
    {
        if (empty($data['lat']) || empty($data['lng'])) {
            return;
        }

        $parts[] = "CASE WHEN latitude IS NOT NULL AND longitude IS NOT NULL THEN
            GREATEST(0, ? * (1 - (
                (? * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) / ?
            ))) ELSE 0 END";

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

    /** -------------------- STATS -------------------- */

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
