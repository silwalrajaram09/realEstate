<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use App\Models\UserRecommendation;
use Illuminate\Support\Collection;

class UserSuggestionService
{
    public function __construct(
        protected PropertyRecommendationService $recommendationService,
        protected Cosinesimilarityservice $cosineService,
    ) {}

    /**
     * Get suggestions for a user.
     * This now checks the pre-calculated cache table first for better performance.
     */
    public function getSuggestions(?object $user, ?string $query): array
    {
        // 1. If there's a search query, always use the real-time search engine
        if ($query) {
            $properties = Property::approved()
                ->search($query)
                ->take(12)
                ->get();

            $properties = $this->cosineService->rerank($properties, $query);

            return [
                'properties' => $properties,
                'strategyLabel' => 'Search Results'
            ];
        }

        // 2. For authenticated users, check the pre-calculated recommendations table
        if ($user) {
            $cached = UserRecommendation::where('user_id', $user->id)
                ->with('property')
                ->orderByDesc('score')
                ->take(12)
                ->get();

            if ($cached->isNotEmpty()) {
                $properties = $cached->pluck('property')->map(function ($property, $i) use ($cached) {
                    $recommendation = $cached[$i];
                    $property->hybrid_score = $recommendation->score;
                    $property->hybrid_reasons = $recommendation->reasons;
                    
                    // Map cached reasons back to attributes for the UI
                    $property->hybrid_content_score = $recommendation->reasons['content'] ?? 0;
                    $property->hybrid_collab_score = $recommendation->reasons['collab'] ?? 0;
                    $property->hybrid_pop_score = $recommendation->reasons['pop'] ?? 0;
                    
                    return $property;
                });

                return [
                    'properties' => $properties,
                    'strategyLabel' => 'Personalised for you (Optimized)'
                ];
            }
        }

        // 3. Fallback to real-time hybrid calculation if cache is empty
        return $this->calculateRealtimeSuggestions($user);
    }

    /**
     * The original real-time logic as a fallback
     */
    public function calculateRealtimeSuggestions(?object $user): array
    {
        $properties = collect();
        $strategyLabel = 'Recommended for you';
        $referenceProperty = null;

        // Try to find an anchor property to base recommendations on
        if ($user) {
            $latestFavorite = Favorite::where('user_id', $user->id)->latest()->first();
            if ($latestFavorite) {
                $referenceProperty = $latestFavorite->property;
                $strategyLabel = 'Based on your favorites';
            }

            if (!$referenceProperty) {
                $latestView = PropertyView::where('user_id', $user->id)->latest()->first();
                if ($latestView) {
                    $referenceProperty = $latestView->property;
                    $strategyLabel = 'Based on recently viewed';
                }
            }
        }

        // Guest anchor
        if (!$user) {
            $sessionIds = session()->get('recently_viewed', []);
            if (!empty($sessionIds)) {
                $referenceProperty = Property::find($sessionIds[0]);
                if ($referenceProperty) {
                    $strategyLabel = 'Based on your browsing history';
                }
            }
        }

        if ($referenceProperty) {
            $properties = $this->recommendationService->getSimilarProperties($referenceProperty, $user?->id, 12);
        }

        // Global Hybrid Fallback (Popularity based)
        if ($properties->isEmpty()) {
            $properties = Property::approved()
                ->orderByDesc('views_count')
                ->take(12)
                ->get()
                ->map(function ($property) use ($user) {
                    $pop = $this->recommendationService->calculatePopularity($property);
                    $collab = $user ? $this->recommendationService->calculateCollaborative($user->id, $property->id) : 0;
                    $property->hybrid_pop_score = $pop;
                    $property->hybrid_collab_score = $collab;
                    $property->hybrid_score = (0.3 * $collab) + (0.2 * $pop);
                    return $property;
                })
                ->values();
                
            if ($properties->isNotEmpty()) {
                $strategyLabel = 'Hybrid Recommendations';
            }
        }

        return [
            'properties' => $properties,
            'strategyLabel' => $strategyLabel
        ];
    }
}
