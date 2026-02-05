<?php

namespace App\Http\Controllers\buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Property;
use App\Services\PropertySearchService;

class BuyerPropertyController extends Controller
{
    // Browse all properties
    public function index(Request $request, PropertySearchService $service)
    {
        $filters = $request->only(['purpose', 'type', 'category', 'q', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort']);
        $properties = $service->search($filters);

        return view('buyer.properties.index', compact('properties'));
    }

    // Buy intent (pre-filtered)
    public function buy(Request $request, PropertySearchService $service)
    {
        $filters = array_merge(
            $request->only(['type', 'category', 'q', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort']),
            ['purpose' => 'buy']
        );

        $properties = $service->search($filters);

        return view('buyer.properties.buy', compact('properties'));
    }

    // Load more properties for infinite scroll
    public function loadMore(Request $request, PropertySearchService $service): JsonResponse
    {
        $page = (int) $request->get('page', 2);
        $perPage = min((int) $request->get('per_page', 12), 24);

        $filters = $request->only(['purpose', 'type', 'category', 'q', 'min_price', 'max_price', 'bedrooms', 'bathrooms', 'sort']);
        $filters['page'] = $page;
        $filters['per_page'] = $perPage;

        $properties = $service->search($filters);

        // Return JSON response for AJAX requests
        return response()->json([
            'properties' => $properties->items(),
            'next_page_url' => $properties->nextPageUrl(),
            'has_more' => $properties->hasMorePages(),
            'current_page' => $properties->currentPage(),
            'last_page' => $properties->lastPage(),
            'total' => $properties->total(),
        ]);
    }

    // Property detail
    public function show(Property $property)
    {
        $recommendations = Property::where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->limit(6)
            ->get();

        return view('buyer.properties.show', compact('property', 'recommendations'));
    }

    // Suggestions
    public function suggestions()
    {
        $properties = Property::latest()->take(8)->get();

        return view('buyer.suggestions.index', compact('properties'));
    }
}
