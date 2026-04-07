<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\Cache;

class PropertySearchService
{
    protected const CACHE_TTL = 15; // minutes

    public function __construct(
        protected CosineSimilarityService $cosine
    ) {}

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

        $perPage    = min(max((int) ($data['per_page'] ?? 12), 6), 48);
        $hasTextSearch = !empty($data['q']);

        // -------------------- TEXT SEARCH + COSINE RE-RANKING --------------------
        if ($hasTextSearch) {
            $search = strtolower(trim($data['q']));
            $like   = '%' . $search . '%';

            // Step 1: broad DB filter — LIKE on indexed columns to get candidates
            $query->where(function ($q) use ($like) {
                $q->whereRaw('LOWER(location) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(title) LIKE ?', [$like])
                  ->orWhereRaw('LOWER(description) LIKE ?', [$like]);
            });

            // Step 2: fetch candidates (up to 5× the page size for re-ranking pool)
            $candidates = $query
                ->select('properties.*')
                ->orderByRaw("
                    CASE WHEN LOWER(location) LIKE ? THEN 0
                         WHEN LOWER(title)    LIKE ? THEN 1
                         ELSE 2
                    END
                ", [$like, $like])
                ->limit($perPage * 5)
                ->get();

            // Step 3: cosine re-ranking in PHP — TF-IDF weighted, corpus-aware IDF
            $reranked = $this->cosine->rerank($candidates, $data['q']);

            // Step 4: manual pagination over re-ranked collection
            $page  = (int) request()->get('page', 1);
            $items = $reranked->forPage($page, $perPage);

            return new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $reranked->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        // -------------------- SORTING (non-text-search) --------------------
        $query->select('properties.*');
        $sort = $data['sort'] ?? 'latest';

        match ($sort) {
            'price_asc'  => $query->orderByDesc('is_featured')->orderBy('price', 'asc'),
            'price_desc' => $query->orderByDesc('is_featured')->orderBy('price', 'desc'),
            'oldest'     => $query->orderByDesc('is_featured')->orderBy('created_at', 'asc'),
            default      => $query->orderByDesc('is_featured')->orderBy('created_at', 'desc'),
        };

        if (!isset($data['cursor'])) {
            return $query->paginate($perPage);
        }

        return $query->cursorPaginate($perPage, ['*'], 'cursor', $data['cursor']);
    }

    public function loadMore(array $data, int $page = 2, int $perPage = 12)
    {
        $data['page']     = $page;
        $data['per_page'] = $perPage;
        return $this->search($data);
    }

    protected function generateCacheKey(array $data): string
    {
        $relevantData = [
            'purpose'   => $data['purpose'] ?? null,
            'type'      => $data['type'] ?? null,
            'category'  => $data['category'] ?? null,
            'min_price' => $data['min_price'] ?? null,
            'max_price' => $data['max_price'] ?? null,
            'bedrooms'  => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'sort'      => $data['sort'] ?? 'latest',
            'page'      => request()->get('page', 1),
            'per_page'  => $data['per_page'] ?? 12,
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
                'total'        => Property::approved()->count(),
                'for_sale'     => Property::approved()->where('purpose', 'buy')->count(),
                'for_rent'     => Property::approved()->where('purpose', 'rent')->count(),
                'avg_price'    => Property::approved()->avg('price'),
                'avg_bedrooms' => Property::approved()->avg('bedrooms'),
            ];
        });
    }

    public function recommend(array $data)
    {
        if (empty($data['min_price']) && empty($data['max_price'])) {
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

        $minTolerance = $data['min_price'] - ($data['min_price'] * 0.10);
        $maxTolerance = $data['max_price'] + ($data['max_price'] * 0.10);

        $results = Property::approved()
            ->when(!empty($data['type']), fn($q) => $q->where('type', $data['type']))
            ->when(!empty($data['category']), fn($q) => $q->where('category', $data['category']))
            ->when(!empty($data['q']), fn($q) => $q->where(
                fn($sub) => $sub->where('title', 'LIKE', "%{$data['q']}%")
                    ->orWhere('location', 'LIKE', "%{$data['q']}%")
            ))
            ->whereBetween('price', [$minTolerance, $maxTolerance])
            ->orderByRaw("ABS(price - ?) ASC", [($data['min_price'] + $data['max_price']) / 2])
            ->limit(50) // get wider pool for cosine re-ranking
            ->get();

        // If there's a text query, re-rank with cosine before returning top 10
        if (!empty($data['q'])) {
            return $this->cosine->rerank($results, $data['q'])->take(10);
        }

        return $results->take(10);
    }
}