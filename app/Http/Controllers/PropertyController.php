<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\PropertySearchService;

class PropertyController extends Controller
{
    /**
     * Unified property listing/search method
     */
    public function list(Request $request, PropertySearchService $service)
    {
        // Get all filters from request
        $filters = $request->only(['purpose', 'type', 'category', 'q']);

        // Use service to get filtered properties
        $properties = $service->search($filters);

        return view('properties.list', compact('properties'));
    }

    /**
     * Show single property with recommendations
     */
    public function show(Property $property)
    {
        $recommendations = Property::query()
            ->where('id', '!=', $property->id)
            ->where('type', $property->type)
            ->whereBetween('price', [
                $property->price * 0.9,
                $property->price * 1.1
            ])
            ->orderByRaw("
                (location = ?) DESC,
                ABS(price - ?) ASC
            ", [$property->location, $property->price])
            ->limit(6)
            ->get();

        return view('properties.show', compact('property', 'recommendations'));
    }
}
