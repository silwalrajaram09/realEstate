<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $validated = $request->validate([
            // Basic Info
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'purpose' => 'required|in:buy,rent',
            'type' => 'required|in:flat,house,land,commercial,office,warehouse',
            'category' => 'required|in:residential,commercial,industrial',

            // Pricing & Location
            'price' => 'required|numeric|min:1',
            'min_lease_months' => 'nullable|required_if:purpose,rent|in:1,3,6,12,24',
            'location' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Size & Images
            'area' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Residential Fields
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor_no' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),

            // Land Fields
            'road_access' => 'nullable|integer|min:0',
            'facing' => 'nullable|in:east,west,north,south,northeast,northwest,southeast,southwest',
            'land_shape' => 'nullable|in:rectangle,square,irregular,triangular',
            'plot_number' => 'nullable|string|max:50',

            // Commercial Fields
            'parking_spaces' => 'nullable|integer|min:0',

            // Industrial Fields
            'clear_height' => 'nullable|numeric|min:0',
            'loading_docks' => 'nullable|integer|min:0',
            'power_supply' => 'nullable|integer|min:0',

            // Features
            'parking' => 'nullable|boolean',
            'water' => 'nullable|boolean',
            'electricity' => 'nullable|boolean',
            'security' => 'nullable|boolean',
            'garden' => 'nullable|boolean',
            'balcony' => 'nullable|boolean',
            'gym' => 'nullable|boolean',
            'lift' => 'nullable|boolean',
            'ac' => 'nullable|boolean',
            'fire_safety' => 'nullable|boolean',
            'internet' => 'nullable|boolean',
            'loading_area' => 'nullable|boolean',

            // Availability
            'available_from' => 'nullable|date|after_or_equal:today',
            // 'status' => 'nullable|in:available,under_negotiation,sold',

            // Ownership
            'ownership_type' => 'nullable|in:freehold,leasehold,cooperative',
            'contact_number' => 'required|string|max:20',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $validated['image'] = $imageName;
        }

        // Set default values for checkboxes
        $booleanFields = [
            'parking',
            'water',
            'electricity',
            'security',
            'garden',
            'balcony',
            'gym',
            'lift',
            'ac',
            'fire_safety',
            'internet',
            'loading_area'
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field) ? true : false;
        }

        // Set default status
        $validated['status'] = $request->status ?? 'pending';

        // Assign seller
        $validated['user_id'] = Auth::id();

        // Create property
        $property = Property::create($validated);

        return redirect()
            ->route('seller.properties.index')
            ->with('success', 'Property listed successfully!');
    }

    /**
     * Show single property
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

        $validated = $request->validate([
            // Basic Info
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'purpose' => 'required|in:buy,rent',
            'type' => 'required|in:flat,house,land,commercial,office,warehouse',
            'category' => 'required|in:residential,commercial,industrial',

            // Pricing & Location
            'price' => 'required|numeric|min:1',
            'min_lease_months' => 'nullable|required_if:purpose,rent|in:1,3,6,12,24',
            'location' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Size & Images
            'area' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Residential Fields
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floor_no' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),

            // Land Fields
            'road_access' => 'nullable|integer|min:0',
            'facing' => 'nullable|in:east,west,north,south,northeast,northwest,southeast,southwest',
            'land_shape' => 'nullable|in:rectangle,square,irregular,triangular',
            'plot_number' => 'nullable|string|max:50',

            // Commercial Fields
            'parking_spaces' => 'nullable|integer|min:0',

            // Industrial Fields
            'clear_height' => 'nullable|numeric|min:0',
            'loading_docks' => 'nullable|integer|min:0',
            'power_supply' => 'nullable|integer|min:0',

            // Features
            'parking' => 'nullable|boolean',
            'water' => 'nullable|boolean',
            'electricity' => 'nullable|boolean',
            'security' => 'nullable|boolean',
            'garden' => 'nullable|boolean',
            'balcony' => 'nullable|boolean',
            'gym' => 'nullable|boolean',
            'lift' => 'nullable|boolean',
            'ac' => 'nullable|boolean',
            'fire_safety' => 'nullable|boolean',
            'internet' => 'nullable|boolean',
            'loading_area' => 'nullable|boolean',

            // Availability
            'available_from' => 'nullable|date',

            // Ownership
            'ownership_type' => 'nullable|in:freehold,leasehold,cooperative',
            'contact_number' => 'required|string|max:20',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($property->image && Storage::exists('public/images/' . $property->image)) {
                Storage::delete('public/images/' . $property->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $validated['image'] = $imageName;
        }

        // Set default values for checkboxes
        $booleanFields = [
            'parking',
            'water',
            'electricity',
            'security',
            'garden',
            'balcony',
            'gym',
            'lift',
            'ac',
            'fire_safety',
            'internet',
            'loading_area'
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field) ? true : false;
        }

        $property->update($validated);

        return redirect()
            ->route('seller.properties.index')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Delete property
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete image if exists
        if ($property->image && Storage::exists('public/images/' . $property->image)) {
            Storage::delete('public/images/' . $property->image);
        }

        $property->delete();

        return redirect()
            ->route('seller.properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}

