<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use Illuminate\Support\Collection;

class UserSuggestionService
{
    public function __construct(
        protected PropertyRecommendationService $recommendationService,
        protected Cosinesimilarityservice $cosineService,
    ) {}

    public function getSuggestions(?object $user, ?string $query): array
    {
        $properties = collect();
        $strategyLabel = '';

        if ($user) {
            $favorites = Favorite::where('user_id', $user->id)
                ->with('property')
                ->latest()
                ->take(5)
                ->get()
                ->pluck('property')
                ->filter();

            if ($favorites->isNotEmpty()) {
                $preferences = $this->extractPreferences($favorites);
                $properties = $this->recommendationService->personalized($preferences, 12);
                $strategyLabel = 'Based on your favorites';
            }
        }

        if ($properties->isEmpty() && $user) {
            $recentViews = PropertyView::where('user_id', $user->id)
                ->with('property')
                ->latest()
                ->take(10)
                ->get()
                ->pluck('property')
                ->filter();

            if ($recentViews->isNotEmpty()) {
                $preferences = $this->extractPreferences($recentViews);
                $properties = $this->recommendationService->personalized($preferences, 12);
                $strategyLabel = 'Based on recently viewed';
            }
        }

        if ($properties->isEmpty() && !$user) {
            $sessionIds = session()->get('recently_viewed', []);
            if (!empty($sessionIds)) {
                $sessionViewed = Property::whereIn('id', $sessionIds)
                    ->approved()
                    ->get()
                    ->sortBy(fn($p) => array_search($p->id, $sessionIds))
                    ->values();

                if ($sessionViewed->isNotEmpty()) {
                    $preferences = $this->extractPreferences($sessionViewed);
                    $properties = $this->recommendationService->personalized($preferences, 12);
                    $strategyLabel = 'Based on your browsing history';
                }
            }
        }

        if ($properties->isEmpty() && $user && $user->city) {
            $properties = Property::approved()
                ->where('location', 'LIKE', "%{$user->city}%")
                ->inRandomOrder()
                ->take(12)
                ->get();
            $strategyLabel = 'Properties in your city';
        }

        // Strategy 6: Hybrid recommendations as fallback
        if ($properties->isEmpty() && $user) {
            $properties = Property::approved()
                ->inRandomOrder()
                ->take(20)
                ->get()
                ->map(function ($property) use ($user) {
                    // Let's call getHybridFallback or similar in recommendation service?
                    // Actually, getting random and assigning score is not ideal, but we can score them.
                    $pop = $this->recommendationService->calculatePopularity($property);
                    $collab = $this->recommendationService->calculateCollaborative($user->id, $property->id);
                    $property->hybrid_pop_score = $pop;
                    $property->hybrid_collab_score = $collab;
                    $property->hybrid_content_score = 0; // fallback has no content ref
                    $property->hybrid_score = (0.3 * $collab) + (0.2 * $pop);
                    return $property;
                })
                ->filter(fn($p) => $p->hybrid_score > 0.1)
                ->sortByDesc('hybrid_score')
                ->take(12)
                ->values();
                
            if ($properties->isNotEmpty()) {
                $strategyLabel = 'Hybrid Recommendations';
            }
        }

        if ($properties->isEmpty()) {
            $properties = Property::approved()
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get();
            $strategyLabel = 'Trending Properties';
        }

        if ($query && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $query);
            $strategyLabel = 'Search Results';
        }

        return [
            'properties' => $properties,
            'strategyLabel' => $strategyLabel
        ];
    }

    private function extractPreferences($properties): array
    {
        return [
            'purpose'   => $this->getMostFrequent($properties, 'purpose'),
            'type'      => $this->getMostFrequent($properties, 'type'),
            'category'  => $this->getMostFrequent($properties, 'category'),
            'min_price' => $properties->min('price') * 0.8,
            'max_price' => $properties->max('price') * 1.2,
            'bedrooms'  => $this->getMostFrequent($properties, 'bedrooms'),
        ];
    }

    private function getMostFrequent($collection, string $field): mixed
    {
        $values = $collection->pluck($field)->filter();
        if ($values->isEmpty()) return null;
        $counts = array_count_values($values->toArray());
        arsort($counts);
        return key($counts);
    }
}
