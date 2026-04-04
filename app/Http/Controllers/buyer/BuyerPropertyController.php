<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyView;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerPropertyController extends Controller
{
    public function __construct(private readonly PropertyService $properties) {}

    /** Browse all properties with filters and cosine re-ranking. */
    public function index(Request $request)
    {
        $properties = $this->properties->search($request->only([
            'purpose', 'type', 'category', 'q',
            'min_price', 'max_price', 'bedrooms', 'bathrooms',
            'sort', 'lat', 'lng',
        ]));

        return view('buyer.properties.index', compact('properties'));
    }

    /** Pre-filtered view for properties available to buy. */
    public function buy(Request $request)
    {
        $properties = $this->properties->search(array_merge(
            $request->only(['type', 'category', 'q', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort', 'lat', 'lng']),
            ['purpose' => 'buy']
        ));

        return view('buyer.properties.buy', compact('properties'));
    }

    /** Property detail page — tracks view, loads similar properties. */
    public function show(Property $property)
    {
        abort_unless($property->status === 'approved', 404);

        $property->incrementViews();
        $this->trackView($property);

        return view('buyer.properties.show', [
            'property'        => $property,
            'recommendations' => $this->properties->similar($property),
            'recentlyViewed'  => $this->recentlyViewed($property),
        ]);
    }

    /** Infinite-scroll endpoint. */
    public function loadMore(Request $request): JsonResponse
    {
        $filters         = $request->only(['purpose', 'type', 'category', 'q', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort']);
        $filters['page'] = (int) $request->get('page', 2);
        $perPage         = min((int) $request->get('per_page', 12), 24);

        $result = $this->properties->search($filters, $perPage);

        return response()->json([
            'properties'    => $result->items(),
            'next_page_url' => $result->nextPageUrl(),
            'has_more'      => $result->hasMorePages(),
            'current_page'  => $result->currentPage(),
            'total'         => $result->total(),
        ]);
    }

    /** Properties near a coordinate. */
    public function nearby(Request $request)
    {
        $lat    = $request->float('lat');
        $lng    = $request->float('lng');
        $radius = $request->integer('radius', 10);

        abort_unless($lat && $lng, 422, 'Location coordinates required.');

        $properties = $this->properties->nearby($lat, $lng, $radius);

        return view('buyer.properties.index', compact('properties'));
    }
    public function suggestions(Request $request)
    {
        $properties = $this->properties->personalized(Auth::user(), 12);
 
        return view('buyer.suggestions.index', compact('properties'));
    }
 

    /** Market stats (used by dashboard widgets via AJAX). */
    public function stats(): JsonResponse
    {
        return response()->json($this->properties->stats());
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function trackView(Property $property): void
    {
        if ($user = Auth::user()) {
            PropertyView::updateOrCreate(
                ['user_id' => $user->id, 'property_id' => $property->id],
                ['created_at' => now()]
            );
        } else {
            $viewed = collect(session('recently_viewed', []))
                ->reject(fn ($id) => $id == $property->id)
                ->prepend($property->id)
                ->take(10)
                ->values()
                ->all();

            session(['recently_viewed' => $viewed]);
        }
    }

    private function recentlyViewed(Property $exclude): iterable
    {
        if ($user = Auth::user()) {
            return Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.user_id', $user->id)
                ->where('properties.id', '!=', $exclude->id)
                ->where('properties.status', 'approved')
                ->orderByDesc('property_views.created_at')
                ->select('properties.*')
                ->distinct()
                ->limit(10)
                ->get();
        }

        $ids = session('recently_viewed', []);

        return Property::whereIn('id', $ids)
            ->approved()
            ->where('id', '!=', $exclude->id)
            ->get()
            ->sortBy(fn ($p) => array_search($p->id, $ids));
    }
}