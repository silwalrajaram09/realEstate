<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Services\PropertyService;

//use App\Services\PropertySearchService;
class PropertyController extends Controller
{
    /**
     * Unified property listing/search method
     */
    public function list(Request $request)
    {
        $cosine = app(CosineSimilarityService::class);
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

        $query = Property::approved()->with('seller');
        Property::scopeFilters($query, $filters); // model scopes
        $properties = $query->paginate($filters['per_page'] ?? 12);

        $prefVec = $cosine->prefsToVector($filters);
        $scoredProps = $properties->getCollection()
            ->map(fn($p) => ['p' => $p, 'score' => $cosine->cosine($prefVec, $cosine->vectorize($p))])
            ->sortByDesc('score')
            ->pluck('p');

        $paginated = new LengthAwarePaginator($scoredProps, $properties->total(), $properties->perPage(), $properties->currentPage());
        $paginated->setPath(request()->url());

        return view('properties.list', compact('properties', $paginated));
    }

    /**
     * Show single property with recommendations
     */
    public function show(Property $property)
    {
        $cosine = app(CosineSimilarityService::class);
        $recommendations = $cosine->rankSimilar($property);
        return view('properties.show', compact('property', 'recommendations'));
    }
}
