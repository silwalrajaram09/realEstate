<?php

namespace App\Http\Controllers\buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Property;
use App\Models\PropertyView;
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

    /**
     * Browse all properties with filters, weighted scoring, and cosine re-ranking.
     */
    public function index(Request $request)
    {
        $filters = $this->extractFilters($request);

        // RecommendationService already blends SQL score + cosine internally
        $properties = $this->recommendationService->search($filters);

        // Pass cosine meta to view so blade can show a "relevance" badge if needed
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.index', compact('properties', 'filters', 'hasTextSearch'));
    }

    /**
     * Properties for buying (pre-filtered to purpose=buy).
     */
    public function buy(Request $request)
    {
        $filters = array_merge(
            $this->extractFilters($request, excludes: ['purpose']),
            ['purpose' => 'buy']
        );

        $properties = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.buy', compact('properties', 'filters', 'hasTextSearch'));
    }

    /**
     * Properties for renting (pre-filtered to purpose=rent).
     */
    public function rent(Request $request)
    {
        $filters = array_merge(
            $this->extractFilters($request, excludes: ['purpose']),
            ['purpose' => 'rent']
        );

        $properties = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.rent', compact('properties', 'filters', 'hasTextSearch'));
    }

    /*
    |--------------------------------------------------------------------------
    | Property Detail
    |--------------------------------------------------------------------------
    */

    /**
     * Property detail with cosine-powered similar properties and recently viewed.
     */
    public function show(Property $property)
    {
        if ($property->status !== 'approved') {
            abort(404, 'Property not found or not available.');
        }

        // Track view
        $property->incrementViews();
        $this->trackView($property);

        // Load recently viewed
        $recentlyViewedProperties = $this->getRecentlyViewed($property);

        // Similar properties — cosine re-ranks using property's own text as the query
        $recommendations = $this->recommendationService->getSimilarProperties($property);

        return view('buyer.properties.show', [
            'property'            => $property,
            'recommendations'     => $recommendations,
            'recentlyViewed'      => $recentlyViewedProperties,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Infinite Scroll / Load More
    |--------------------------------------------------------------------------
    */

    /**
     * Load more properties for infinite scroll (returns JSON).
     * Cosine re-ranking is applied inside recommendationService->search().
     */
    public function loadMore(Request $request): JsonResponse
    {
        $filters = $this->extractFilters($request);
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
    | Personalized & Nearby
    |--------------------------------------------------------------------------
    */

    /**
     * Personalized suggestions based on user preferences + cosine re-ranking.
     * If a text query is present, cosine re-ranks the personalized pool.
     */
    public function suggestions(Request $request)
    {
        $preferences = $request->only([
            'purpose', 'type', 'category',
            'min_price', 'max_price',
            'bedrooms', 'bathrooms',
            'lat', 'lng',
            'q',  // ← cosine kicks in when present
        ]);

        $properties = $this->recommendationService->personalized($preferences, limit: 12);

        return view('buyer.suggestions.index', compact('properties', 'preferences'));
    }

    /**
     * Properties within a radius. If a text query is also provided,
     * cosine re-ranks the nearby results by text relevance too.
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

        // Optional: if user also typed a text query, re-rank nearby results with cosine
        if ($query && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $query);
        }

        return view('buyer.properties.nearby', compact('properties', 'lat', 'lng', 'radius', 'query'));
    }

    /*
    |--------------------------------------------------------------------------
    | Search (SearchService — lightweight, cached)
    |--------------------------------------------------------------------------
    */

    /**
     * Quick search endpoint — uses PropertySearchService (cached, cosine re-ranked).
     * Use this for search bars and autocomplete-style results.
     */
    public function search(Request $request): JsonResponse
    {
        $data = $request->only([
            'q', 'purpose', 'type', 'category',
            'min_price', 'max_price',
            'bedrooms', 'bathrooms',
            'sort', 'per_page',
        ]);

        // SearchService handles cosine re-ranking internally for text queries
        $results = $this->searchService->search($data);

        return response()->json([
            'properties'    => $results->items(),
            'has_more'      => $results->hasMorePages(),
            'current_page'  => $results->currentPage(),
            'total'         => $results->total(),
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
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Extract standard filter fields from the request.
     *
     * @param  string[] $excludes  Fields to skip (e.g. 'purpose' when hardcoding it)
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
     * Load the recently viewed properties list for the current user or guest.
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