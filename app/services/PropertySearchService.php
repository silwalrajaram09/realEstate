<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Cache;

class PropertySearchService
{
    protected const CACHE_TTL = 15; // minutes

    public function search(array $data, bool $useCache = true)
    {
        $cacheKey = $this->generateCacheKey($data);

        if ($useCache && empty($data['q'])) {
            return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_TTL), fn() => $this->executeSearch($data));
        }

        return $this->executeSearch($data);
    }

    protected function executeSearch(array $data)
    {
        $query = Property::query()->approved();

        // -------------------- FILTERS --------------------
        foreach (['purpose', 'type', 'category'] as $field) {
            if (!empty($data[$field])) {
                $query->where($field, $data[$field]);
            }
        }

        if (!empty($data['min_price']) && !empty($data['max_price'])) {
            $query->whereBetween('price', [$data['min_price'], $data['max_price']]);
        } elseif (!empty($data['min_price'])) {
            $query->where('price', '>=', $data['min_price']);
        } elseif (!empty($data['max_price'])) {
            $query->where('price', '<=', $data['max_price']);
        }

        if (!empty($data['bedrooms'])) {
            $query->where('bedrooms', '>=', (int) $data['bedrooms']);
        }

        if (!empty($data['bathrooms'])) {
            $query->where('bathrooms', '>=', (int) $data['bathrooms']);
        }

        // -------------------- TEXT SEARCH --------------------
        if (!empty($data['q'])) {
            $search = strtolower(trim($data['q']));

            // SIMPLE DIRECT SEARCH - No complex regional grouping
            // This fixes the bug where searching "surkhet" didn't return Surkhet properties

            $like = '%' . $search . '%';

            // Build simple scoring conditions
            // Scoring:
            // - Match in location = highest (10 pts) - most important for location search
            // - Match in title = medium (5 pts)
            // - Match in description = lower (2 pts)

            $query->selectRaw("
                properties.*,
                (
                    CASE WHEN LOWER(location) LIKE ? THEN 10 ELSE 0 END +
                    CASE WHEN LOWER(title) LIKE ? THEN 5 ELSE 0 END +
                    CASE WHEN LOWER(description) LIKE ? THEN 2 ELSE 0 END
                ) AS relevance_score
            ", [$like, $like, $like])
                ->orderByDesc('relevance_score');
        } else {
            $query->select('properties.*');
        }

        // -------------------- SORTING --------------------
        // When text search is used, always prioritize relevance score
        // Only use date/price sorting when no text search or explicitly requested
        $sort = $data['sort'] ?? 'relevance';

        // If relevance sorting or no sort specified with text search, skip additional sorting
        $hasTextSearch = !empty($data['q']);

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'oldest' && !$hasTextSearch) {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'latest' && !$hasTextSearch) {
            $query->orderBy('created_at', 'desc');
        } elseif ($hasTextSearch) {
            // With text search: relevance is primary, use created_at as tie-breaker (newest first)
            $query->orderByDesc('created_at');
        }

        $perPage = min(max((int) ($data['per_page'] ?? 12), 6), 48);

        if (!isset($data['cursor'])) {
            return $query->paginate($perPage);
        }

        return $query->cursorPaginate($perPage, ['*'], 'cursor', $data['cursor']);
    }

    public function loadMore(array $data, int $page = 2, int $perPage = 12)
    {
        $data['page'] = $page;
        $data['per_page'] = $perPage;
        return $this->search($data);
    }

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

    public function clearCache(): void
    {
        Cache::tags(['properties'])->flush();
    }

    public function getPopularSearchTerms(int $limit = 5): array
    {
        return ['kathmandu', 'lalitpur', 'bhaktapur', 'flat', 'house'];
    }

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

    // public function recommend(array $data)
    // {
    //     return Property::approved()
    //         ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
    //         ->when(!empty($data['category']), fn($q) => $q->where('category', $data['category']))
    //         ->when(!empty($data['q']), fn($q) => $q->where(
    //             fn($sub) => $sub
    //                 ->where('title', 'LIKE', "%{$data['q']}%")
    //                 ->orWhere('location', 'LIKE', "%{$data['q']}%")
    //         ))
    //         ->orderByRaw("ABS(price - ?) ASC", [$data['max_price'] ?? 0])
    //         ->limit(10)
    //         ->get();
    // }

    //min and max price range scoring with tolerance
    public function recommend(array $data)
    {
        if (empty($data['min_price']) && empty($data['max_price'])) {
            // fallback: just use max_price for sorting if no min-max
            return Property::approved()
                ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
                ->when(!empty($data['category']), fn($q) => $q->where('category', $data['category']))
                ->when(!empty($data['q']), fn($q) => $q->where(
                    fn($sub) => $sub->where('title', 'LIKE', "%{$data['q']}%")
                        ->orWhere('location', 'LIKE', "%{$data['q']}%")
                ))
                ->orderByRaw("ABS(price - ?) ASC", [$data['max_price'] ?? 0])
                ->limit(10)
                ->get();
        }

        // Calculate tolerance
        $minTolerance = $data['min_price'] - ($data['min_price'] * 0.10); // 10% below min
        $maxTolerance = $data['max_price'] + ($data['max_price'] * 0.10); // 10% above max

        return Property::approved()
            ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
            ->when(!empty($data['category']), fn($q) => $q->where('category', $data['category']))
            ->when(!empty($data['q']), fn($q) => $q->where(
                fn($sub) => $sub->where('title', 'LIKE', "%{$data['q']}%")
                    ->orWhere('location', 'LIKE', "%{$data['q']}%")
            ))
            ->whereBetween('price', [$minTolerance, $maxTolerance])
            ->orderByRaw("ABS(price - ?) ASC", [($data['min_price'] + $data['max_price']) / 2])
            ->limit(10)
            ->get();
    }

    //     $minTolerance = $data['min_price'] - ($data['min_price'] * 0.10); // 10% below min
    //     $maxTolerance = $data['max_price'] + ($data['max_price'] * 0.10); // 10% above max

    //     return Property::approved()
    //         ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
    //         ->when(!empty($data['category']), fn($q) => $q->where('category', $data['category']))
    //         ->when(!empty($data['q']), fn($q) => $q->where(
    //             fn($sub) => $sub->where('title', 'LIKE', "%{$data['q']}%")
    //                 ->orWhere('location', 'LIKE', "%{$data['q']}%")
    //         ))
    //         ->whereBetween('price', [$minTolerance, $maxTolerance])
    //         ->orderByRaw("ABS(price - ?) ASC", [($data['min_price'] + $data['max_price']) / 2])
    //         ->limit(10)
    //         ->get();
    // }

}
