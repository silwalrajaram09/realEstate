<?php

namespace App\Http\Controllers\buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Property;
use App\Models\PropertyView;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Services\UserSuggestionService;
use App\Services\PropertyRecommendationService;

class BuyerPropertyController extends Controller
{
    public function __construct(
        protected PropertyRecommendationService $recommendationService,
        protected UserSuggestionService $userSuggestionService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Browse Pages
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $filters = $this->extractFilters($request);
        $properties = $this->recommendationService->search($filters);
        $hasTextSearch = !empty($filters['q']);

        return view('buyer.properties.index', compact('properties', 'filters', 'hasTextSearch'));
    }

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

    public function show(Property $property)
    {
        $isAvailable = $property->status === 'approved' && (($property->listing_status ?? 'available') === 'available');
        if (!$isAvailable) {
            abort(404, 'Property not found or not available.');
        }

        $property->incrementViews();
        $this->trackView($property);

        $recentlyViewed = $this->getRecentlyViewed($property);
        $recommendations = $this->recommendationService->getSimilarProperties($property, 6, Auth::id());
        $recommendationSectionTitle = 'Similar Properties';

        if ($recommendations->isEmpty()) {
            $recommendations = Property::approved()
                ->where('id', '!=', $property->id)
                ->where('purpose', $property->purpose)
                ->orderByDesc('is_featured')
                ->orderByDesc('views_count')
                ->latest()
                ->take(6)
                ->get()
                ->each(function ($p) {
                    $p->similarity_score = null;
                    $p->recommendation_reason = 'Popular choice you may like';
                });

            $recommendationSectionTitle = 'You May Like';
        }

        return view('buyer.properties.show', compact('property', 'recommendations', 'recentlyViewed', 'recommendationSectionTitle'));
    }

    /*
    |--------------------------------------------------------------------------
    | Load More (infinite scroll)
    |--------------------------------------------------------------------------
    */

    public function loadMore(Request $request): JsonResponse
    {
        $filters = $this->extractFilters($request);
        $filters['page'] = (int) $request->get('page', 2);
        $filters['per_page'] = min((int) $request->get('per_page', 12), 24);

        $properties = $this->recommendationService->search($filters);

        return response()->json([
            'properties' => $properties->items(),
            'next_page_url' => $properties->nextPageUrl(),
            'has_more' => $properties->hasMorePages(),
            'current_page' => $properties->currentPage(),
            'total' => $properties->total(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Personalized Suggestions
    |--------------------------------------------------------------------------
    */

    public function suggestions(Request $request)
    {
        $user = Auth::user();
        $urlQuery = $request->get('q');

        $result = $this->userSuggestionService->getSuggestions($user, $urlQuery);

        $properties = $result['properties'];
        $strategyLabel = $result['strategyLabel'];

        return view('buyer.suggestions.index', compact('properties', 'strategyLabel'));
    }

    /*
    |--------------------------------------------------------------------------
    | Nearby
    |--------------------------------------------------------------------------
    */

    public function nearby(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $radius = (int) $request->get('radius', 10);
        $query = $request->get('q');

        if (!$lat || !$lng) {
            return redirect()->back()->with('error', 'Location coordinates required.');
        }

        $properties = $this->recommendationService->withinRadius($lat, $lng, $radius);

        // Text search handling within nearby properties (Generic PHP Filter)
        if ($query && $properties->isNotEmpty()) {
            $properties = $properties->filter(function ($property) use ($query) {
                return stripos($property->title, $query) !== false 
                    || stripos($property->location, $query) !== false;
            })->values();
        }

        return view('buyer.properties.nearby', compact('properties', 'lat', 'lng', 'radius', 'query'));
    }

    /*
    |--------------------------------------------------------------------------
    | Search (JSON)
    |--------------------------------------------------------------------------
    */

    public function search(Request $request): JsonResponse
    {
        $data = $request->only(['q', 'purpose', 'type', 'category', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort', 'per_page']);
        $results = $this->recommendationService->search($data);

        return response()->json([
            'properties' => $results->items(),
            'has_more' => $results->hasMorePages(),
            'current_page' => $results->currentPage(),
            'total' => $results->total(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Stats / Enquiry
    |--------------------------------------------------------------------------
    */

    public function getStats(): JsonResponse
    {
        return response()->json($this->recommendationService->stats());
    }

    public function enquire(Request $request, Property $property): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        return response()->json(['message' => 'Your enquiry has been sent successfully!']);
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */



    private function extractFilters(Request $request, array $excludes = []): array
    {
        $allowed = ['purpose', 'type', 'category', 'q', 'min_price', 'max_price', 'min_area', 'max_area', 'bedrooms', 'bathrooms', 'sort', 'sort_order', 'lat', 'lng', 'radius', 'per_page'];
        $fields = array_diff($allowed, $excludes);

        return array_filter(
            $request->only($fields),
            fn($v) => $v !== null && $v !== ''
        );
    }

    private function trackView(Property $property): void
    {
        if ($user = Auth::user()) {
            PropertyView::updateOrCreate(
                ['user_id' => $user->id, 'property_id' => $property->id],
                ['buyer_id' => $user->id, 'viewed_at' => now()]
            );
        } else {
            $viewed = session()->get('recently_viewed', []);
            $viewed = array_filter($viewed, fn($id) => $id != $property->id);
            array_unshift($viewed, $property->id);
            session()->put('recently_viewed', array_slice($viewed, 0, 10));
        }
    }

    private function getRecentlyViewed(Property $property): \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
    {
        if ($user = Auth::user()) {
            return Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where(function ($q) use ($user) {
                    $q->where('property_views.buyer_id', $user->id)->orWhere('property_views.user_id', $user->id);
                })
                ->where('properties.id', '!=', $property->id)
                ->where('properties.status', 'approved')
                ->where(function ($q) {
                    $q->whereNull('properties.listing_status')->orWhere('properties.listing_status', 'available');
                })
                ->orderByDesc('property_views.viewed_at')
                ->select('properties.*')
                ->distinct()
                ->take(10)
                ->get();
        }

        $ids = session()->get('recently_viewed', []);

        return Property::whereIn('id', $ids)
            ->approved()
            ->where('id', '!=', $property->id)
            ->get()
            ->sortBy(fn($p) => array_search($p->id, $ids))
            ->values();
    }
}