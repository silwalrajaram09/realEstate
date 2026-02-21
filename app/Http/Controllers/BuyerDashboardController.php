<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use App\Services\PropertyRecommendationService;
use Illuminate\Support\Facades\Auth;

class BuyerDashboardController extends Controller
{
    protected $recommendationService;

    /**
     * Inject the recommendation service
     */
    public function __construct(PropertyRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the buyer's complete dashboard with personalized recommendations.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // ===== SECTION 1: STATISTICS =====
        $stats = [
            'total_properties' => Property::approved()->count(),
            'for_sale' => Property::approved()->where('purpose', 'buy')->count(),
            'for_rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg_price' => Property::approved()->avg('price'),
            'new_today' => Property::approved()->whereDate('created_at', today())->count(),
        ];

        // ===== SECTION 2: USER-SPECIFIC STATS =====
        $userStats = [];
        if ($user) {
            $userStats = [
                'total_views' => PropertyView::where('user_id', $user->id)->count(),
                'total_favorites' => Favorite::where('user_id', $user->id)->count(),
                'total_inquiries' => 0, // Add if you have inquiries table
            ];
        }

        // ===== SECTION 3: RECENTLY VIEWED PROPERTIES =====
        $recentlyViewed = collect();
        if ($user) {
            // Get from database for logged-in users
            $recentlyViewed = Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.user_id', $user->id)
                ->where('properties.status', 'approved')
                ->orderBy('property_views.created_at', 'desc')
                ->select('properties.*')
                ->distinct()
                ->take(6)
                ->get();
        } else {
            // Get from session for guests
            $recentlyViewedIds = session()->get('recently_viewed', []);
            if (!empty($recentlyViewedIds)) {
                $recentlyViewed = Property::whereIn('id', $recentlyViewedIds)
                    ->approved()
                    ->get()
                    ->sortBy(function ($prop) use ($recentlyViewedIds) {
                        return array_search($prop->id, $recentlyViewedIds);
                    });
            }
        }

        // ===== SECTION 4: PERSONALIZED RECOMMENDATIONS =====
        $personalizedRecommendations = $this->getPersonalizedRecommendations($user);

        // ===== SECTION 5: TRENDING/POPULAR PROPERTIES =====
        $trendingProperties = Property::approved()
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();

        // ===== SECTION 6: NEW LISTINGS =====
        $newListings = Property::approved()
            ->latest()
            ->take(6)
            ->get();

        // ===== SECTION 7: PROPERTY TYPE BREAKDOWN =====
        $propertyTypes = [
            'apartments' => Property::approved()->where('type', 'flat')->count(),
            'houses' => Property::approved()->where('type', 'house')->count(),
            'land' => Property::approved()->where('type', 'land')->count(),
            'commercial' => Property::approved()->where('type', 'commercial')->count(),
        ];

        // ===== SECTION 8: LOCATION-BASED SUGGESTIONS =====
        $nearbyProperties = collect();
        if ($user && $user->latitude && $user->longitude) {
            $nearbyProperties = $this->recommendationService->withinRadius(
                $user->latitude,
                $user->longitude,
                10 // 10km radius
            )->take(6);
        }

        return view('buyer.dashboard', compact(
            'stats',
            'userStats',
            'recentlyViewed',
            'personalizedRecommendations',
            'trendingProperties',
            'newListings',
            'propertyTypes',
            'nearbyProperties'
        ));
    }

    /**
     * Get personalized recommendations based on user behavior
     */
    private function getPersonalizedRecommendations($user)
    {
        if (!$user) {
            return Property::approved()->inRandomOrder()->take(6)->get();
        }

        // Strategy 1: Based on favorites (strongest signal)
        $favorites = Favorite::where('user_id', $user->id)
            ->with('property')
            ->latest()
            ->take(5)
            ->get()
            ->pluck('property');

        if ($favorites->isNotEmpty()) {
            $preferences = $this->extractPreferences($favorites);
            $recommendations = $this->recommendationService->personalized($preferences, 6);
            if ($recommendations->isNotEmpty()) {
                return $recommendations;
            }
        }

        // Strategy 2: Based on recently viewed (strong signal)
        $recentViews = PropertyView::where('user_id', $user->id)
            ->with('property')
            ->latest()
            ->take(10)
            ->get()
            ->pluck('property');

        if ($recentViews->isNotEmpty()) {
            $preferences = $this->extractPreferences($recentViews);
            $recommendations = $this->recommendationService->personalized($preferences, 6);
            if ($recommendations->isNotEmpty()) {
                return $recommendations;
            }
        }

        // Strategy 3: Based on user's city/location
        if ($user->city) {
            $cityRecommendations = Property::approved()
                ->where('location', 'LIKE', "%{$user->city}%")
                ->inRandomOrder()
                ->take(6)
                ->get();

            if ($cityRecommendations->isNotEmpty()) {
                return $cityRecommendations;
            }
        }

        // Strategy 4: Fallback to trending properties
        return Property::approved()
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get();
    }

    /**
     * Extract preferences from a collection of properties
     */
    private function extractPreferences($properties)
    {
        return [
            'purpose' => $this->getMostFrequent($properties, 'purpose'),
            'type' => $this->getMostFrequent($properties, 'type'),
            'category' => $this->getMostFrequent($properties, 'category'),
            'min_price' => $properties->min('price') * 0.8,
            'max_price' => $properties->max('price') * 1.2,
            'bedrooms' => $this->getMostFrequent($properties, 'bedrooms'),
        ];
    }

    /**
     * Helper to get most frequent value from collection
     */
    private function getMostFrequent($collection, $field)
    {
        $values = $collection->pluck($field)->filter();
        if ($values->isEmpty()) {
            return null;
        }

        $counts = array_count_values($values->toArray());
        arsort($counts);
        return key($counts);
    }
}