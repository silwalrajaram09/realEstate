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
use App\Services\CosineSimilarityService;

class BuyerPropertyController extends Controller
{
    public function __construct(
        protected PropertySearchService $searchService,
        protected PropertyRecommendationService $recommendationService,
        protected CosineSimilarityService $cosineService,
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

        return view('buyer.properties.show', compact('property', 'recommendations', 'recentlyViewed'));
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
    |
    | Flow:
    |   1. Determine which strategy has data (favorites → views → session → city → trending)
    |   2. Extract SQL preference filters from that data (loose — cosine does fine ranking)
    |   3. Call recommendationService->personalized($preferences, 12, $userId)
    |      └── Stage 1: SQL loose filter → wide candidate pool
    |      └── Stage 2: Numeric cosine (buildUserVector → rankForUser) → top 12 with scores
    |   4. If user also typed ?q= → re-rank the 12 by text cosine (Engine A) on top
    |   5. Pass to blade: properties with ->similarity_score, $strategyLabel
    |--------------------------------------------------------------------------
    */

    public function suggestions(Request $request)
    {
        $user = Auth::user();
        $properties = collect();
        $strategyNum = 5;

        // ── Strategy 1: Favorites ──────────────────────────────────────────
        if ($user) {
            $favorites = Favorite::where('user_id', $user->id)
                ->with('property')
                ->latest()
                ->take(20)
                ->get()
                ->pluck('property')
                ->filter();

            if ($favorites->isNotEmpty()) {
                $properties = $this->recommendationService->personalized(
                    preferences: $this->extractPreferences($favorites),
                    limit: 12,
                    userId: $user->id,
                );
                $strategyNum = 1;
            }
        }

        // ── Strategy 2: Recently viewed (DB) ──────────────────────────────
        if ($properties->isEmpty() && $user) {
            $views = PropertyView::where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)->orWhere('user_id', $user->id);
            })
                ->with('property')
                ->orderByDesc('viewed_at')
                ->take(20)
                ->get()
                ->pluck('property')
                ->filter();

            if ($views->isNotEmpty()) {
                $properties = $this->recommendationService->personalized(
                    preferences: $this->extractPreferences($views),
                    limit: 12,
                    userId: $user->id,
                );
                $strategyNum = 2;
            }
        }

        // ── Strategy 3: Recently viewed (session — guests) ─────────────────
        if ($properties->isEmpty() && !$user) {
            $sessionIds = session()->get('recently_viewed', []);

            if (!empty($sessionIds)) {
                $sessionProps = Property::whereIn('id', $sessionIds)->approved()
                    ->get()
                    ->sortBy(fn($p) => array_search($p->id, $sessionIds))
                    ->values();

                if ($sessionProps->isNotEmpty()) {
                    // For guests: no userId → Stage 2 skips numeric cosine,
                    // returns SQL-scored pool. Still better than random.
                    $properties = $this->recommendationService->personalized(
                        preferences: $this->extractPreferences($sessionProps),
                        limit: 12,
                        userId: null,
                    );
                    $strategyNum = 3;
                }
            }
        }

        // ── Strategy 4: User city ──────────────────────────────────────────
        if ($properties->isEmpty() && $user?->city) {
            $properties = Property::approved()
                ->where('location', 'LIKE', "%{$user->city}%")
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get()
                ->each(fn($p) => $p->similarity_score = null);
            $strategyNum = 4;
        }

        // ── Strategy 5: Trending fallback ─────────────────────────────────
        if ($properties->isEmpty()) {
            $properties = Property::approved()
                ->orderBy('views_count', 'desc')
                ->take(12)
                ->get()
                ->each(fn($p) => $p->similarity_score = null);
            $strategyNum = 5;
        }

        // ── Text cosine re-rank (if user typed ?q=) ────────────────────────
        // Engine A runs on TOP of Engine B results.
        // The numeric cosine already ordered by behavior; text cosine refines
        // within that by the typed query.
        $urlQuery = $request->get('q');
        if ($urlQuery && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $urlQuery);
            // rerank() sets ->cosine_score AND ->similarity_score
        }

        $strategyLabel = $this->resolveStrategyLabel($strategyNum, $urlQuery);

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

        if ($query && $properties->isNotEmpty()) {
            $properties = $this->cosineService->rerank($properties, $query);
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
        $results = $this->searchService->search($data);

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

    /**
     * Build SQL preference filters from a collection of Property models.
     * These are LOOSE filters — cosine does the fine-grained ranking.
     */
    private function extractPreferences(\Illuminate\Support\Collection $properties): array
    {
        return [
            'purpose' => $this->getMostFrequent($properties, 'purpose'),
            'type' => $this->getMostFrequent($properties, 'type'),
            'category' => $this->getMostFrequent($properties, 'category'),
            // Widen price range: cosine handles properties priced slightly outside
            'min_price' => $properties->min('price') * 0.7,
            'max_price' => $properties->max('price') * 1.3,
            'bedrooms' => $this->getMostFrequent($properties, 'bedrooms'),
        ];
    }

    private function getMostFrequent(\Illuminate\Support\Collection $collection, string $field): mixed
    {
        $values = $collection->pluck($field)->filter();
        if ($values->isEmpty())
            return null;

        $counts = array_count_values($values->toArray());
        arsort($counts);
        return key($counts);
    }

    private function resolveStrategyLabel(int $strategy, ?string $urlQuery): string
    {
        if ($urlQuery) {
            return 'Search-matched & cosine ranked';
        }

        return match ($strategy) {
            1 => 'Ranked by your saved favourites',
            2 => 'Ranked by your recently viewed',
            3 => 'Based on your browsing history',
            4 => 'Properties near your city',
            default => 'Trending properties',
        };
    }

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