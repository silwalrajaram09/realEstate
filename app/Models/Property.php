<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        // Basic Info
        'title',
        'description',
        'purpose',
        'type',
        'category',

        // Pricing & Location
        'price',
        'min_lease_months',
        'location',
        'latitude',
        'longitude',

        // Size & Images
        'area',
        'image',

        // Residential Fields
        'bedrooms',
        'bathrooms',
        'floor_no',
        'year_built',

        // Commercial Fields
        'total_floors',
        'parking_spaces',

        // Land Fields
        'road_access',
        'facing',
        'land_shape',
        'plot_number',

        // Industrial Fields
        'clear_height',
        'loading_docks',
        'power_supply',

        // Features & Amenities
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
        'loading_area',

        // Availability & Status
        'available_from',
        'status',

        // Ownership
        'ownership_type',
        'contact_number',

        // Relations
        'user_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'area' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floor_no' => 'integer',
        'total_floors' => 'integer',
        'year_built' => 'integer',
        'road_access' => 'integer',
        'parking_spaces' => 'integer',
        'clear_height' => 'decimal:2',
        'loading_docks' => 'integer',
        'power_supply' => 'integer',
        'parking' => 'boolean',
        'water' => 'boolean',
        'electricity' => 'boolean',
        'security' => 'boolean',
        'garden' => 'boolean',
        'balcony' => 'boolean',
        'gym' => 'boolean',
        'lift' => 'boolean',
        'ac' => 'boolean',
        'fire_safety' => 'boolean',
        'internet' => 'boolean',
        'loading_area' => 'boolean',
        'available_from' => 'date',
    ];

    /**
     * Get the seller (user) that owns the property.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to filter by purpose.
     */
    public function scopePurpose($query, $purpose)
    {
        return $query->when($purpose, function ($q) use ($purpose) {
            $q->where('purpose', $purpose);
        });
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeType($query, $type)
    {
        return $query->when($type, function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, $category)
    {
        return $query->when($category, function ($q) use ($category) {
            $q->where('category', $category);
        });
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        return $query->when($minPrice, function ($q) use ($minPrice) {
            $q->where('price', '>=', $minPrice);
        })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            });
    }

    /**
     * Scope a query to filter by minimum bedrooms.
     */
    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->when($bedrooms, function ($q) use ($bedrooms) {
            $q->where('bedrooms', '>=', (int) $bedrooms);
        });
    }

    /**
     * Scope a query to filter by minimum bathrooms.
     */
    public function scopeMinBathrooms($query, $bathrooms)
    {
        return $query->when($bathrooms, function ($q) use ($bathrooms) {
            $q->where('bathrooms', '>=', (int) $bathrooms);
        });
    }

    /**
     * Scope a query for text search.
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        });
    }

    /**
     * Scope a query to order by price.
     */
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Scope a query for residential properties.
     */
    public function scopeResidential($query)
    {
        return $query->where('category', 'residential');
    }

    /**
     * Scope a query for commercial properties.
     */
    public function scopeCommercial($query)
    {
        return $query->where('category', 'commercial');
    }

    /**
     * Scope a query for industrial properties.
     */
    public function scopeIndustrial($query)
    {
        return $query->where('category', 'industrial');
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        $prefix = $this->purpose === 'rent' ? 'Rs/month' : 'Rs';
        return $prefix . ' ' . number_format($this->price);
    }

    /**
     * Get main image URL.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('images/' . $this->image) : asset('images/image1.jpg');
    }

    /**
     * Get property type label.
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'flat' => 'Flat/Apartment',
            'house' => 'House',
            'land' => 'Land/Plot',
            'commercial' => 'Commercial Space',
            'office' => 'Office',
            'warehouse' => 'Warehouse',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get purpose label.
     */
    public function getPurposeLabelAttribute()
    {
        return $this->purpose === 'rent' ? 'For Rent' : 'For Sale';
    }

    /**
     * Get category label.
     */
    public function getCategoryLabelAttribute()
    {
        $labels = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
        ];

        return $labels[$this->category] ?? $this->category;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Check if property is available.
     */
    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }

    /**
     * Get all features as an array.
     */
    public function getFeaturesAttribute()
    {
        $features = [];

        if ($this->parking)
            $features[] = 'Parking';
        if ($this->water)
            $features[] = 'Water Supply';
        if ($this->electricity)
            $features[] = 'Electricity';
        if ($this->security)
            $features[] = 'Security';
        if ($this->garden)
            $features[] = 'Garden';
        if ($this->balcony)
            $features[] = 'Balcony';
        if ($this->gym)
            $features[] = 'Gym';
        if ($this->lift)
            $features[] = 'Lift';
        if ($this->ac)
            $features[] = 'AC';
        if ($this->fire_safety)
            $features[] = 'Fire Safety';
        if ($this->internet)
            $features[] = 'Internet';
        if ($this->loading_area)
            $features[] = 'Loading Area';

        return $features;
    }
}

