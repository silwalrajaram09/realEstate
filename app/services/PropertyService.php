<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PropertyService
{
    private const EARTH_RADIUS = 6371;
    private const LOCATION_RADIUS = 50;
    private const CACHE_TTL = 3600;

    public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $cosine = app(CosineSimilarityService::class);

        $query = Property::query()->approved()->with('seller');
        $this->applyFilters($query, $filters);

        // Fetch enough data before ranking
        $results = $query->limit(100)->get();

        $prefVec = $cosine->prefsToVector($filters);

        $sorted = $results
            ->map(function ($p) use ($cosine, $prefVec, $filters) {

                $cosineScore = $cosine->cosine($prefVec, $cosine->vectorize($p));

                // 🔥 Distance score
                $distanceScore = 0;

                if (isset($filters['lat'], $filters['lng']) && $p->latitude && $p->longitude) {
                    $distance = $this->calculateDistance(
                        $filters['lat'],
                        $filters['lng'],
                        $p->latitude,
                        $p->longitude
                    );

                    // exponential decay (better than linear)
                    $distanceScore = exp(-$distance / 10);
                }

                // 🔥 Final hybrid score
                $finalScore = (0.7 * $cosineScore) + (0.3 * $distanceScore);

                return [
                    'property' => $p,
                    'score' => $finalScore,
                ];
            })
            ->sortByDesc('score')
            ->pluck('property')
            ->values();

        // Manual pagination
        $page = request()->get('page', 1);
        $items = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $sorted->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function similar(Property $property, int $limit = 6): Collection
    {
        return app(CosineSimilarityService::class)->rankSimilar($property, $limit);
    }

    public function personalized(?object $user, int $limit = 6): Collection
    {
        if (!$user) {
            return Property::approved()->latest('views_count')->limit($limit)->get();
        }

        $favourites = $user->favorites()
            ->with('property')
            ->latest()
            ->limit(5)
            ->get()
            ->pluck('property')
            ->filter();

        if ($favourites->isNotEmpty()) {

            $profile = [];
            $totalWeight = 0;

            foreach ($favourites->values() as $i => $p) {
                $w = 1 / ($i + 1);
                $totalWeight += $w;

                foreach (['price','bedrooms','bathrooms','latitude','longitude'] as $f) {
                    $profile[$f] = ($profile[$f] ?? 0) + ($p->{$f} ?? 0) * $w;
                }

                foreach (['type','purpose','category'] as $f) {
                    $profile[$f] = $p->{$f};
                }
            }

            foreach ($profile as $k => $v) {
                if ($totalWeight > 0) {
                    $profile[$k] = $v / $totalWeight;
                }
            }

            return $this->similar($this->syntheticProperty($profile), $limit);
        }

        return Property::approved()->latest('views_count')->limit($limit)->get();
    }

    public function nearby(float $lat, float $lng, int $radius = 10): Collection
    {
        return Property::query()
            ->approved()
            ->whereNotNull(['latitude', 'longitude'])
            ->tap(fn($q) => $this->applyBoundingBox($q, $lat, $lng, $radius))
            ->selectRaw('properties.*, ' . $this->haversineSQL() . ' AS distance', $this->haversineBindings($lat, $lng))
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    public function stats(): array
    {
        return Cache::remember('property_stats', self::CACHE_TTL, fn() => [
            'total' => Property::approved()->count(),
            'sale' => Property::approved()->where('purpose', 'buy')->count(),
            'rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg' => (int) Property::approved()->avg('price'),
        ]);
    }

    private function applyFilters(Builder $q, array $d): void
    {
        $q->purpose($d['purpose'] ?? null)
            ->type($d['type'] ?? null)
            ->category($d['category'] ?? null)
            ->priceRange($d['min_price'] ?? null, $d['max_price'] ?? null)
            ->minBedrooms($d['bedrooms'] ?? null)
            ->minBathrooms($d['bathrooms'] ?? null)
            ->search($d['q'] ?? null);

        if (isset($d['lat'], $d['lng'])) {
            $lat = (float) $d['lat'];
            $lng = (float) $d['lng'];
            $radius = (int) ($d['radius'] ?? self::LOCATION_RADIUS);

            $this->applyBoundingBox($q, $lat, $lng, $radius);

            $q->selectRaw(
                'properties.*, ' . $this->haversineSQL() . ' AS distance',
                $this->haversineBindings($lat, $lng)
            )
            ->having('distance', '<=', $radius)
            ->orderBy('distance');
        }
    }

    private function applyBoundingBox(Builder $q, float $lat, float $lng, int $radius): void
    {
        $dLat = $radius / 111;
        $dLng = $radius / (111 * cos(deg2rad($lat)));

        $q->whereBetween('latitude', [$lat - $dLat, $lat + $dLat])
          ->whereBetween('longitude', [$lng - $dLng, $lng + $dLng]);
    }

    private function haversineSQL(): string
    {
        return '? * acos(LEAST(1.0,
            cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(latitude))
        ))';
    }

    private function haversineBindings(float $lat, float $lng): array
    {
        return [self::EARTH_RADIUS, $lat, $lng, $lat];
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS * $c;
    }

    private function syntheticProperty(array $profile): Property
    {
        $p = new Property(['id' => 0]);

        foreach ($profile as $k => $v) {
            $p->{$k} = $v;
        }

        return $p;
    }
}