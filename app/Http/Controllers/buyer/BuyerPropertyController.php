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

class BuyerPropertyController extends Controller
{
    protected $searchService;
    protected $recommendationService;

    public function __construct(
        PropertySearchService $searchService,
        PropertyRecommendationService $recommendationService
    ) {
        $this->searchService = $searchService;
        $this->recommendationService = $recommendationService;
    }

    /**
     * Browse all properties with filters and weighted scoring.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'purpose',
            'type',
            'category',
            'q',
            'min_price',
            'max_price',
            'bedrooms',
            'bathrooms',
            'sort',
            'sort_order',
            'lat',
            'lng'
        ]);

        // Use recommendation service for scored results
        $properties = $this->recommendationService->search($filters);

        return view('buyer.properties.index', compact('properties'));
    }

    /**
     * Properties for buying (pre-filtered).
     */
    public function buy(Request $request)
    {
        $filters = array_merge(
            $request->only([
                'type',
                'category',
                'q',
                'min_price',
                'max_price',
                'bedrooms',
                'bathrooms',
                'sort',
                'lat',
                'lng'
            ]),
            ['purpose' => 'buy']
        );

        $properties = $this->recommendationService->search($filters);

        return view('buyer.properties.buy', compact('properties'));
    }

    /**
     * Load more properties (infinite scroll).
     */
    public function loadMore(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 2);
        $perPage = min((int) $request->get('per_page', 12), 24);

        $filters = $request->only([
            'purpose',
            'type',
            'category',
            'q',
            'min_price',
            'max_price',
            'bedrooms',
            'bathrooms',
            'sort',
            'sort_order'
        ]);
        $filters['page'] = $page;
        $filters['per_page'] = $perPage;

        $properties = $this->recommendationService->search($filters);

        return response()->json([
            'properties' => $properties->items(),
            'next_page_url' => $properties->nextPageUrl(),
            'has_more' => $properties->hasMorePages(),
            'current_page' => $properties->currentPage(),
            'total' => $properties->total(),
        ]);
    }

    /**
     * Property detail with similar properties and recently viewed.
     */
    public function show(Property $property)
    {
        // Check if property is approved
        if ($property->status !== 'approved') {
            abort(404, 'Property not found or not available.');
        }

        // Increment views_count (requires views_count column in properties table)
        $property->incrementViews();

        // Track view in DB for logged-in users
        if ($user = Auth::user()) {
            PropertyView::updateOrCreate(
                ['user_id' => $user->id, 'property_id' => $property->id],
                ['created_at' => now()] // updates timestamp if already exists
            );
        } else {
            // Track for guests in session
            $recentlyViewed = session()->get('recently_viewed', []);
            $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id != $property->id);
            array_unshift($recentlyViewed, $property->id);
            $recentlyViewed = array_slice($recentlyViewed, 0, 10);
            session()->put('recently_viewed', $recentlyViewed);
        }

        // Load recently viewed properties
        if ($user) {
            $recentlyViewedProperties = Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.user_id', $user->id)
                ->where('properties.id', '!=', $property->id)
                ->where('properties.status', 'approved')
                ->orderBy('property_views.created_at', 'desc')
                ->select('properties.*')
                ->distinct()
                ->take(10)
                ->get();
        } else {
            $recentlyViewed = session()->get('recently_viewed', []);
            $recentlyViewedProperties = Property::whereIn('id', $recentlyViewed)
                ->approved()
                ->where('id', '!=', $property->id)
                ->get()
                ->sortBy(function ($prop) use ($recentlyViewed) {
                    return array_search($prop->id, $recentlyViewed);
                });
        }

        // Get similar recommendations
        $recommendations = $this->recommendationService->getSimilarProperties($property);

        return view('buyer.properties.show', [
            'property' => $property,
            'recommendations' => $recommendations,
            'recentlyViewed' => $recentlyViewedProperties,
        ]);
    }

    /**
     * Personalized suggestions based on user preferences.
     */
    public function suggestions(Request $request)
    {
        // Get user preferences from request or session
        $preferences = $request->only([
            'purpose',
            'type',
            'category',
            'min_price',
            'max_price',
            'bedrooms',
            'bathrooms',
            'lat',
            'lng'
        ]);

        // Get personalized recommendations using weighted scoring
        $properties = $this->recommendationService->personalized(
            $preferences,
            12
        );

        return view('buyer.suggestions.index', compact('properties'));
    }

    /**
     * Search properties near a location.
     */
    public function nearby(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = $request->get('radius', 10); // km

        if (!$lat || !$lng) {
            return redirect()->back()->with('error', 'Location coordinates required.');
        }

        $properties = $this->recommendationService->withinRadius($lat, $lng, $radius);

        return view('buyer.properties.index', compact('properties'));
    }

    /**
     * Get stats for dashboard.
     */
    public function getStats(): JsonResponse
    {
        return response()->json($this->recommendationService->stats());
    }
}