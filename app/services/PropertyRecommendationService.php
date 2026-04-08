<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\PropertyView;
use Illuminate\Support\Collection;

class PropertyRecommendationService
{
    /**
     * Get similar properties (Simple Hybrid: Content-Based + Collaborative)
     */
    public function getSimilarProperties(Property $property, int $limit = 6, ?int $userId = null): Collection
    {
        $excludeIds = collect([$property->id]);
        if ($userId) {
            $excludeIds = $excludeIds->merge($this->getUserHistoryIds($userId));
        }

        // Fetch properties with same purpose (buy/rent) to evaluate
        $candidates = Property::query()
            ->approved()
            ->withCount('favorites')
            ->where('purpose', $property->purpose)
            ->whereNotIn('id', $excludeIds)
            ->take(150) // Pool size
            ->get();

        return $this->rankCandidates($candidates, $property, $limit);
    }

    /**
     * Get personalized suggestions (Simple Hybrid: User Profile + Collaborative)
     */
    public function getPersonalizedSuggestions(int $userId, int $limit = 6): Collection
    {
        $historyIds = $this->getUserHistoryIds($userId);
        
        if (empty($historyIds)) {
            return collect(); // Fallback to trending
        }

        // Build a simple profile of what the user likes
        $viewedProperties = Property::whereIn('id', $historyIds)->get();
        if ($viewedProperties->isEmpty()) return collect();

        // Create a 'mock' target property representing the user's average taste
        $targetTaste = new Property();
        $targetTaste->purpose = $this->getMode($viewedProperties, 'purpose');
        $targetTaste->category = $this->getMode($viewedProperties, 'category');
        $targetTaste->type = $this->getMode($viewedProperties, 'type');
        $targetTaste->price = $viewedProperties->avg('price');
        $targetTaste->bedrooms = (int) $viewedProperties->avg('bedrooms');
        $targetTaste->location = $this->getMode($viewedProperties, 'location');

        // Fetch candidates
        $candidates = Property::query()
            ->approved()
            ->withCount('favorites')
            ->where('purpose', $targetTaste->purpose)
            ->whereNotIn('id', $historyIds)
            ->take(150)
            ->get();

        return $this->rankCandidates($candidates, $targetTaste, $limit);
    }

    /**
     * ── THE SIMPLE HYBRID RANKING ENGINE ────────────────────────────────────
     */
    private function rankCandidates(Collection $candidates, Property $target, int $limit): Collection
    {
        return $candidates->map(function ($candidate) use ($target) {
            
            // 1. CONTENT-BASED SCORE (Max 1.0)
            $contentScore = 0.0;
            
            // Exact Matches
            if ($candidate->category === $target->category) $contentScore += 0.3;
            if ($candidate->type === $target->type) $contentScore += 0.2;
            
            // Location Match (basic city extraction)
            if ($target->location && $candidate->location) {
                $targetCity = explode(',', $target->location)[0];
                if (stripos($candidate->location, trim($targetCity)) !== false) {
                    $contentScore += 0.2;
                }
            }

            // Price Match (within 20%)
            if ($target->price > 0 && $candidate->price > 0) {
                $priceDiffRatio = abs($candidate->price - $target->price) / $target->price;
                if ($priceDiffRatio <= 0.20) {
                    $contentScore += (0.2 * (1 - ($priceDiffRatio / 0.20))); // Closer = higher
                }
            }

            // Bedroom Match
            if ($target->bedrooms > 0 && $candidate->bedrooms == $target->bedrooms) {
                $contentScore += 0.1;
            }

            // 2. COLLABORATIVE SCORE (User behavior, Max 1.0)
            $views = $candidate->views_count ?? 0;
            $favorites = $candidate->favorites_count ?? 0;
            // Normalize assuming 300 views is "highly popular", 20 favs is "highly favorited"
            $viewScore = min(1.0, $views / 300);
            $favScore = min(1.0, $favorites / 20);
            $collaborativeScore = ($viewScore * 0.4) + ($favScore * 0.6);

            // 3. POPULARITY & FRESHNESS SCORE (Max 1.0)
            $featuredScore = $candidate->is_featured ? 1.0 : 0.0;
            $daysOld = now()->diffInDays($candidate->created_at ?? now());
            $freshnessScore = max(0.0, 1 - ($daysOld / 30)); // Degrades over 30 days
            $popularityScore = ($featuredScore * 0.6) + ($freshnessScore * 0.4);

            // ── FINAL HYBRID FORMULA ──
            // 60% Content-match + 30% Collaborative-likes + 10% Freshness/Featured
            $finalScore = ($contentScore * 0.6) + ($collaborativeScore * 0.3) + ($popularityScore * 0.1);

            $candidate->similarity_score = round($finalScore, 4);

            return $candidate;

        })
        ->filter(function ($property) {
            // Must have at least fundamentally acceptable similarity
            return $property->similarity_score >= 0.15;
        })
        ->sortByDesc('similarity_score')
        ->take($limit)
        ->values();
    }

    /**
     * ── UTILITIES ────────────────────────────────────────────────────────
     */
    private function getUserHistoryIds(int $userId): array
    {
        $views = PropertyView::where('buyer_id', $userId)->orWhere('user_id', $userId)->pluck('property_id');
        $favs = Favorite::where('user_id', $userId)->pluck('property_id');
        return $views->merge($favs)->unique()->all();
    }

    private function getMode(Collection $collection, string $key): ?string
    {
        $values = $collection->pluck($key)->filter();
        if ($values->isEmpty()) return null;
        
        $counts = array_count_values($values->toArray());
        arsort($counts);
        return key($counts);
    }

    /**
     * ── CONTROLLER SUPPORT METHODS ──────────────────────────────────────────
     */
    public function search(array $filters, int $perPage = 6)
    {
        $query = Property::query()->approved()->with('seller')->orderByDesc('is_featured')->latest();
        if (!empty($filters['purpose'])) $query->where('purpose', $filters['purpose']);
        if (!empty($filters['type'])) $query->where('type', $filters['type']);
        if (!empty($filters['category'])) $query->where('category', $filters['category']);
        if (!empty($filters['min_price'])) $query->where('price', '>=', (float) $filters['min_price']);
        if (!empty($filters['max_price'])) $query->where('price', '<=', (float) $filters['max_price']);
        if (!empty($filters['bedrooms'])) $query->where('bedrooms', '>=', (int) $filters['bedrooms']);
        
        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }
        return $query->paginate(min(max($perPage, 6), 48))->withQueryString();
    }

    public function withinRadius(float $lat, float $lng, int $radius = 10): Collection
    {
        $radiusKm = max(1, $radius);
        return Property::query()
            ->approved()
            ->select('properties.*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    public function stats(): array
    {
        return [
            'total' => Property::approved()->count(),
            'sale' => Property::approved()->where('purpose', 'buy')->count(),
            'rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg' => Property::approved()->avg('price'),
        ];
    }
}