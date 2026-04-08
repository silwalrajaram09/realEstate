<?php

namespace App\Services;

use App\Models\Property;

class UserSuggestionService
{
    public function __construct(
        protected PropertyRecommendationService $recommendationService
    ) {
    }

    public function getSuggestions(?\App\Models\User $user, ?string $urlQuery = null): array
    {
        $properties = collect();
        $strategyNum = 5; // Default: Trending

        // ── Strategy 1 & 2: Authenticated User Behavior (Hybrid Recommender) ──
        if ($user) {
            $properties = $this->recommendationService->getPersonalizedSuggestions($user->id, 12);
            if ($properties->isNotEmpty()) {
                $strategyNum = 1;
            }
        }

        // ── Strategy 3: Guest Session Fallback ────────────────────────────────
        if ($properties->isEmpty() && !$user) {
            $sessionIds = session()->get('recently_viewed', []);
            
            if (!empty($sessionIds)) {
                // For guests, we take their most recently viewed property
                // and seed the 'Similar Properties' logic as a fallback hybrid.
                $lastViewed = Property::approved()->find($sessionIds[0]);
                
                if ($lastViewed) {
                    $properties = $this->recommendationService->getSimilarProperties($lastViewed, 12);
                    $strategyNum = 3;
                }
            }
        }

        // ── Strategy 4: User City Fallback ────────────────────────────────────
        if ($properties->isEmpty() && $user?->city) {
            $properties = Property::approved()
                ->where('location', 'LIKE', "%{$user->city}%")
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get()
                ->each(fn($p) => $p->similarity_score = null);
            $strategyNum = 4;
        }

        // ── Strategy 5: Trending Fallback ─────────────────────────────────────
        if ($properties->isEmpty()) {
            $properties = Property::approved()
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get()
                ->each(fn($p) => $p->similarity_score = null);
            $strategyNum = 5;
        }

        // Text search handling within suggested properties
        if ($urlQuery && $properties->isNotEmpty()) {
            $properties = $properties->filter(function ($property) use ($urlQuery) {
                return stripos($property->title, $urlQuery) !== false 
                    || stripos($property->location, $urlQuery) !== false;
            })->values();
        }

        return [
            'properties' => $properties,
            'strategyLabel' => $this->resolveStrategyLabel($strategyNum, $urlQuery),
        ];
    }

    private function resolveStrategyLabel(int $strategy, ?string $urlQuery): string
    {
        if ($urlQuery) {
            return 'Filtered by search term';
        }

        return match ($strategy) {
            1 => 'Personalized properties based on your activity',
            2 => 'Personalized properties based on your activity',
            3 => 'Based on properties you recently viewed',
            4 => 'Top properties near your city',
            default => 'Trending properties right now',
        };
    }
}
