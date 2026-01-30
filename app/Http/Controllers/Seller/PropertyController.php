<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // only logged-in users
    }

    /**
     * Display a list of seller's properties
     */
    public function index()
    {
        $properties = Property::where('user_id', Auth::id())->latest()->get();
        return view('seller.properties.index', compact('properties'));
    }

    /**
     * Show form to create new property
     */
    public function create()
    {
        return view('seller.properties.create');
    }

    /**
     * Store new property
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'required|in:buy,sell',
            'type' => 'required|in:flat,house,land',
            'category' => 'required|in:residential,commercial',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'required|integer',
            'parking' => 'boolean',
            'water' => 'boolean',
        ]);

        $data['user_id'] = Auth::id(); // assign seller

        Property::create($data);

        return redirect()->route('seller.properties.index')->with('success', 'Property added successfully!');
    }

    /**
     * Show single property (optional)
     */
    public function show(Property $property)
    {
        $this->authorize('view', $property);
        return view('seller.properties.show', compact('property'));
    }

    /**
     * Edit property
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        return view('seller.properties.edit', compact('property'));
    }

    /**
     * Update property
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purpose' => 'required|in:buy,sell',
            'type' => 'required|in:flat,house,land',
            'category' => 'required|in:residential,commercial',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'area' => 'required|integer',
            'parking' => 'boolean',
            'water' => 'boolean',
        ]);

        $property->update($data);

        return redirect()->route('seller.properties.index')->with('success', 'Property updated successfully!');
    }

    /**
     * Delete property
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        $property->delete();
        return redirect()->route('seller.properties.index')->with('success', 'Property deleted!');
    }
}
