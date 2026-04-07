<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $user = Auth::user();

        // Get paginated properties
        $properties = Property::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // ✅ FIX: Add stats for the summary cards
        $stats = [
            'total' => Property::where('user_id', $user->id)->count(),
            'approved' => Property::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending' => Property::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('seller.properties.index', compact('properties', 'stats'));
    }

    public function performance()
    {
        $user = Auth::user();
        $properties = Property::where('user_id', $user->id)
            ->withCount([
                'views as views_events_count',
                'enquiries',
                'favoritedBy',
            ])
            ->latest()
            ->paginate(10);

        return view('seller.properties.performance', compact('properties'));
    }

    public function updateListingStatus(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        $request->validate([
            'listing_status' => 'required|in:available,sold,rented',
        ]);
        $property->update(['listing_status' => $request->listing_status]);
        return back()->with('success', 'Listing status updated.');
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery' => 'nullable|array|max:10',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'gallery_order' => 'nullable|string',

            // Residential Fields (only when the create form shows this section)
            'bedrooms' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->type, ['flat', 'house'], true) && $request->category === 'residential'),
                'integer',
                'min:0',
            ],
            'bathrooms' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->type, ['flat', 'house'], true) && $request->category === 'residential'),
                'integer',
                'min:0',
            ],
            'floor_no' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . date('Y'),

            // Land Fields
            'road_access' => 'nullable|required_if:type,land|integer|min:0',
            'facing' => 'nullable|in:east,west,north,south,northeast,northwest,southeast,southwest',
            'land_shape' => 'nullable|required_if:type,land|in:rectangle,square,irregular,triangular',
            'plot_number' => 'nullable|required_if:type,land|string|max:50',

            // Commercial Fields
            'parking_spaces' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->type, ['commercial', 'office'], true) && $request->category === 'commercial'),
                'integer',
                'min:0',
            ],

            // Industrial Fields
            'clear_height' => [
                'nullable',
                Rule::requiredIf(fn () => $request->type === 'warehouse' && $request->category === 'industrial'),
                'numeric',
                'min:0',
            ],
            'loading_docks' => [
                'nullable',
                Rule::requiredIf(fn () => $request->type === 'warehouse' && $request->category === 'industrial'),
                'integer',
                'min:0',
            ],
            'power_supply' => [
                'nullable',
                Rule::requiredIf(fn () => $request->type === 'warehouse' && $request->category === 'industrial'),
                'integer',
                'min:0',
            ],

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
            'contact_number' => ['required', 'string', 'max:25', 'regex:/^[0-9+\-\s()]{7,25}$/'],
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName);
            $validated['image'] = $imageName;
        }
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images', $name);
                $gallery[] = asset('storage/images/' . $name);
            }
            $ordered = json_decode((string) $request->input('gallery_order'), true);
            if (is_array($ordered) && count($ordered) === count($gallery)) {
                $temp = [];
                foreach ($ordered as $index) {
                    if (isset($gallery[(int) $index])) {
                        $temp[] = $gallery[(int) $index];
                    }
                }
                if (count($temp) === count($gallery)) {
                    $gallery = $temp;
                }
            }
            $validated['gallery'] = $gallery;
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

        // Always enforce moderation workflow for newly created listings.
        $validated['status'] = 'pending';
        $validated['listing_status'] = 'available';

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
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'gallery_order_existing' => 'nullable|string',

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
        $gallery = $property->gallery ?? [];
        $existingOrder = json_decode((string) $request->input('gallery_order_existing'), true);
        if (is_array($existingOrder)) {
            $orderedExisting = [];
            foreach ($existingOrder as $url) {
                if (in_array($url, $gallery, true)) {
                    $orderedExisting[] = $url;
                }
            }
            if (!empty($orderedExisting)) {
                $gallery = $orderedExisting;
            }
        }
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images', $name);
                $gallery[] = asset('storage/images/' . $name);
            }
        }
        $validated['gallery'] = $gallery;

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
    //rejected properties
    /**
     * Display rejected properties.
     */
    public function rejected()
    {
        $user = Auth::user();

        $properties = Property::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get stats for the summary cards
        $stats = [
            'total' => Property::where('user_id', $user->id)->count(),
            'approved' => Property::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending' => Property::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('seller.properties.index', compact('properties', 'stats'));
    }
}

