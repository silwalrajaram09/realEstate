<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\PropertySearchService;
use App\Services\PropertyRecommendationService;

class PropertyController extends Controller
{
    /**
     * Unified property listing/search method
     */
    public function list(Request $request, PropertySearchService $service)
    {
        // Get all filters from request
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
            'per_page'
        ]);

        // Use service to get filtered properties
        $properties = $service->search($filters);
        //$properties = Property::latest()->take(6)->get();
        return view('properties.list', compact('properties'));
    }

    /**
     * Show single property with recommendations
     */
    public function show(Property $property, PropertyRecommendationService $recommendationService)
    {
        $recommendations = $recommendationService->getSimilarProperties($property, 6, auth()->id());

        return view('properties.show', compact('property', 'recommendations'));
    }
}
