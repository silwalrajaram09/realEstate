<?php

namespace App\Http\Controllers\buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Services\PropertySearchService;
use App\Services\PropertyRecommendationService;
use App\Services\Cosinesimilarityservice;

class BuyerPropertyController extends Controller
{
    public function __construct(
        protected PropertySearchService $searchService,
        protected PropertyRecommendationService $recommendationService,
        protected Cosinesimilarityservice $cosineService,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Browse / Listing Pages
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $filters       = $this->extractFilters($request);
        $properties    = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.index', compact('properties', 'filters', 'hasTextSearch'));
    }

    public function buy(Request $request)
    {
        $filters = array_merge(
            $this->extractFilters($request, excludes: ['purpose']),
            ['purpose' => 'buy']
        );

        $properties    = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.buy', compact('properties', 'filters', 'hasTextSearch'));
    }

    public function rent(Request $request)
    {
        $filters = array_merge(
            $this->extractFilters($request, excludes: ['purpose']),
            ['purpose' => 'rent']
        );

        $properties    = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.rent', compact('properties', 'filters', 'hasTextSearch'));
    }

    /*
    |--------------------------------------------------------------------------
    | Property Detail
    |--------------------------------------------------------------------------
    */

    public function show(Property $property)
    {
        if ($property->status !== 'approved') {
            abort(404, 'Property not found or not available.');
        }

        $property->incrementViews();
        $this->trackView($property);

        $recentlyViewedProperties = $this->getRecentlyViewed($property);
        $recommendations          = $this->recommendationService->getSimilarProperties($property);

        return view('buyer.properties.show', [
            'property'        => $property,
            'recommendations' => $recommendations,
            'recentlyViewed'  => $recentlyViewedProperties,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Infinite Scroll / Load More
    |--------------------------------------------------------------------------
    */

    public function loadMore(Request $request): JsonResponse
    {
        $filters             = $this->extractFilters($request);
        $filters['page']     = (int) $request->get('page', 2);
        $filters['per_page'] = min((int) $request->get('per_page', 12), 24);

        $properties = $this->recommendationService->search($filters);

        return response()->json([
            'properties'    => $properties->items(),
            'next_page_url' => $properties->nextPageUrl(),
            'has_more'      => $properties->hasMorePages(),
            'current_page'  => $properties->currentPage(),
            'total'         => $properties->total(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Personalized Suggestions
    |
    | Uses the exact same 5-strategy cascade as BuyerDashboardController so
    | both pages always produce consistent, coherent recommendations.
    | personalized() is called as personalized($preferences, $limit) — a plain
    | Collection is returned, matching the dashboard's existing signature.
    |--------------------------------------------------------------------------
    */

    public function suggestions(Request $request)
    {
        $user       = Auth::user();
        $properties = collect();

        // ── Strategy 1: Favorites (strongest signal) ──────────────────────
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
                $properties  = $this->recommendationService->personalized($preferences, 12);
            }
        }

        // ── Strategy 2: Recently viewed — DB (strong signal) ──────────────
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
                $properties  = $this->recommendationService->personalized($preferences, 12);
            }
        }

        // ── Strategy 3: Recently viewed — session (guests) ────────────────
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
                    $properties  = $this->recommendationService->personalized($preferences, 12);
                }
            }
        }

        // ── Strategy 4: User city ─────────────────────────────────────────
        if ($properties->isEmpty() && $user && $user->city) {
            $properties = Property::approved()
                ->where('location', 'LIKE', "%{$user->city}%")
                ->inRandomOrder()
                ->take(12)
                ->get();
        }

        // ── Strategy 5: Trending fallback ─────────────────────────────────
        if ($properties->isEmpty()) {
            $properties = Property::approved()
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get();
        }

        // ── Cosine re-rank if user typed a text query ──────────────────────
        $query = $request->get('q');
        if ($query && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $query);
        }

        // Label shown in the blade to explain what produced the results
        $strategyLabel = $this->resolveStrategyLabel($user, $query);

        return view('buyer.suggestions.index', compact('properties', 'strategyLabel'));
    }

    /*
    |--------------------------------------------------------------------------
    | Nearby
    |--------------------------------------------------------------------------
    */

    public function nearby(Request $request)
    {
        $lat    = $request->get('lat');
        $lng    = $request->get('lng');
        $radius = (int) $request->get('radius', 10);
        $query  = $request->get('q');

        if (!$lat || !$lng) {
            return redirect()->back()->with('error', 'Location coordinates required.');
        }

        $properties = $this->recommendationService->withinRadius($lat, $lng, $radius);

        if ($query && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $query);
        }

        return view('buyer.properties.nearby', compact('properties', 'lat', 'lng', 'radius', 'query'));
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function search(Request $request): JsonResponse
    {
        $data = $request->only([
            'q', 'purpose', 'type', 'category',
            'min_price', 'max_price',
            'bedrooms', 'bathrooms',
            'sort', 'per_page',
        ]);

        $results = $this->searchService->search($data);

        return response()->json([
            'properties'   => $results->items(),
            'has_more'     => $results->hasMorePages(),
            'current_page' => $results->currentPage(),
            'total'        => $results->total(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Stats
    |--------------------------------------------------------------------------
    */

    public function getStats(): JsonResponse
    {
        return response()->json($this->recommendationService->stats());
    }

    /*
    |--------------------------------------------------------------------------
    | Enquiry
    |--------------------------------------------------------------------------
    */

    public function enquire(Request $request, Property $property): JsonResponse
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // TODO: send email / save to enquiries table

        return response()->json([
            'message' => 'Your enquiry has been sent successfully!',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    | extractPreferences() and getMostFrequent() are identical to the copies
    | in BuyerDashboardController so both always produce the same preference
    | vector from the same data.
    |--------------------------------------------------------------------------
    */

    /**
     * Build a preference array from a collection of Property models.
     */
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

    /**
     * Return the most frequently occurring non-null value for $field.
     */
    private function getMostFrequent($collection, string $field): mixed
    {
        $values = $collection->pluck($field)->filter();

        if ($values->isEmpty()) {
            return null;
        }

        $counts = array_count_values($values->toArray());
        arsort($counts);
        return key($counts);
    }

    /**
     * Human-readable label explaining which strategy produced the results.
     */
    private function resolveStrategyLabel(?object $user, ?string $query): string
    {
        if ($query) {
            return 'Search-matched & cosine ranked';
        }

        if (!$user) {
            return 'Based on your browsing history';
        }

        return 'Personalised for you';
    }

    /**
     * Extract standard filter fields from the request.
     */
    private function extractFilters(Request $request, array $excludes = []): array
    {
        $allowed = [
            'purpose', 'type', 'category',
            'q',
            'min_price', 'max_price',
            'bedrooms', 'bathrooms',
            'sort', 'sort_order',
            'lat', 'lng',
            'per_page',
        ];

        $fields = array_diff($allowed, $excludes);

        return array_filter(
            $request->only($fields),
            fn($v) => $v !== null && $v !== ''
        );
    }

    /**
     * Track a property view for the current user (DB) or guest (session).
     */
    private function trackView(Property $property): void
    {
        if ($user = Auth::user()) {
            PropertyView::updateOrCreate(
                ['user_id' => $user->id, 'property_id' => $property->id],
                ['created_at' => now()]
            );
        } else {
            $recentlyViewed = session()->get('recently_viewed', []);
            $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id != $property->id);
            array_unshift($recentlyViewed, $property->id);
            session()->put('recently_viewed', array_slice($recentlyViewed, 0, 10));
        }
    }

    /**
     * Load the recently viewed list for the current user or guest.
     */
    private function getRecentlyViewed(Property $property): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
    {
        if ($user = Auth::user()) {
            return Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.user_id', $user->id)
                ->where('properties.id', '!=', $property->id)
                ->where('properties.status', 'approved')
                ->orderBy('property_views.created_at', 'desc')
                ->select('properties.*')
                ->distinct()
                ->take(10)
                ->get();
        }

        $recentlyViewed = session()->get('recently_viewed', []);

        return Property::whereIn('id', $recentlyViewed)
            ->approved()
            ->where('id', '!=', $property->id)
            ->get()
            ->sortBy(fn($prop) => array_search($prop->id, $recentlyViewed))
            ->values();
    }
}