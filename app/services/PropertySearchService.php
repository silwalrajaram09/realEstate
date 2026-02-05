<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PropertySearchService
{
    /**
     * Cache duration in minutes.
     */
    protected const CACHE_TTL = 15;

    /**
     * Search and filter properties with optional caching.
     */
    public function search(array $data, bool $useCache = true)
    {
        // Generate a unique cache key based on filters
        $cacheKey = $this->generateCacheKey($data);

        // Try to get from cache if enabled
        if ($useCache && !empty($data['q'])) {
            // Don't cache text searches as they are user-specific
            return $this->executeSearch($data);
        }

        if ($useCache) {
            return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL), function () use ($data) {
                return $this->executeSearch($data);
            });
        }

        return $this->executeSearch($data);
    }

    /**
     * Execute the search query.
     */
    protected function executeSearch(array $data)
    {
        $query = Property::query()
            ->approved();

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        if (!empty($data['purpose'])) {
            $query->where('purpose', $data['purpose']);
        }

        if (!empty($data['type'])) {
            $query->where('type', $data['type']);
        }

        if (!empty($data['category'])) {
            $query->where('category', $data['category']);
        }

        if (!empty($data['min_price']) && !empty($data['max_price'])) {
            $query->whereBetween('price', [
                $data['min_price'],
                $data['max_price']
            ]);
        } elseif (!empty($data['min_price'])) {
            $query->where('price', '>=', $data['min_price']);
        } elseif (!empty($data['max_price'])) {
            $query->where('price', '<=', $data['max_price']);
        }

        // Bedrooms filter
        if (!empty($data['bedrooms'])) {
            $query->where('bedrooms', '>=', (int) $data['bedrooms']);
        }

        // Bathrooms filter
        if (!empty($data['bathrooms'])) {
            $query->where('bathrooms', '>=', (int) $data['bathrooms']);
        }

        /*
        |--------------------------------------------------------------------------
        | TEXT SEARCH + SCORE
        |--------------------------------------------------------------------------
        */

        if (!empty($data['q'])) {
            $search = $data['q'];

            $query->selectRaw("
                properties.*,

                (
                    (title LIKE ?) * 5 +
                    (location LIKE ?) * 4 +
                    (description LIKE ?) * 2
                ) AS relevance_score
            ", [
                "%{$search}%",
                "%{$search}%",
                "%{$search}%"
            ]);

            $query->orderByDesc('relevance_score');
        } else {
            $query->select('properties.*');
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $sort = $data['sort'] ?? 'latest';

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Use cursor pagination for better performance with large datasets
        $perPage = (int) ($data['per_page'] ?? 12);
        $perPage = min(max($perPage, 6), 48); // Clamp between 6 and 48

        // For initial load, use regular pagination
        if (!isset($data['cursor'])) {
            return $query->paginate($perPage);
        }

        // For subsequent loads, use cursor-based pagination
        return $query->cursorPaginate($perPage, ['*'], 'cursor', $data['cursor']);
    }

    /**
     * Get properties for infinite scroll / load more.
     */
    public function loadMore(array $data, int $page = 2, int $perPage = 12)
    {
        $data['page'] = $page;
        $data['per_page'] = $perPage;

        return $this->search($data);
    }

    /**
     * Generate a unique cache key based on filters.
     */
    protected function generateCacheKey(array $data): string
    {
        $relevantData = [
            'purpose' => $data['purpose'] ?? null,
            'type' => $data['type'] ?? null,
            'category' => $data['category'] ?? null,
            'min_price' => $data['min_price'] ?? null,
            'max_price' => $data['max_price'] ?? null,
            'bedrooms' => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'sort' => $data['sort'] ?? 'latest',
        ];

        return 'properties_' . md5(serialize($relevantData));
    }

    /**
     * Clear the properties cache.
     */
    public function clearCache(): void
    {
        Cache::tags(['properties'])->flush();
    }

    /**
     * Get popular search terms (for suggestions).
     */
    public function getPopularSearchTerms(int $limit = 5): array
    {
        // This could be extended to track actual search terms
        return ['kathmandu', 'lalitpur', 'bhaktapur', 'flat', 'house'];
    }

    /**
     * Get property statistics.
     */
    public function getStats(): array
    {
        return Cache::remember('property_stats', now()->addHour(), function () {
            return [
                'total' => Property::approved()->count(),
                'for_sale' => Property::approved()->where('purpose', 'buy')->count(),
                'for_rent' => Property::approved()->where('purpose', 'rent')->count(),
                'avg_price' => Property::approved()->avg('price'),
                'avg_bedrooms' => Property::approved()->avg('bedrooms'),
            ];
        });
    }

    /**
     * Recommend properties based on user preferences.
     */
    public function recommend(array $data)
    {
        return Property::approved()
            ->when(
                $data['type'],
                fn($q) => $q->where('type', $data['type'])
            )
            ->when(
                $data['category'],
                fn($q) => $q->where('category', $data['category'])
            )
            ->when($data['q'], function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('location', 'LIKE', "%{$search}%");
                });
            })
            ->orderByRaw("ABS(price - ?) ASC", [$data['max_price'] ?? 0])
            ->limit(10)
            ->get();
    }
}
