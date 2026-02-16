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
        'title',
        'description',
        'purpose',
        'type',
        'category',
        'price',
        'min_lease_months',
        'location',
        'latitude',
        'longitude',
        'area',
        'image',
        'bedrooms',
        'bathrooms',
        'floor_no',
        'year_built',
        'total_floors',
        'parking_spaces',
        'road_access',
        'facing',
        'land_shape',
        'plot_number',
        'clear_height',
        'loading_docks',
        'power_supply',
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
        'available_from',
        'status',
        'ownership_type',
        'contact_number',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /*
    |--------------------------------------------------------------------------
    | Filter Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePurpose($query, $purpose)
    {
        return $query->when($purpose, fn($q) => $q->where('purpose', $purpose));
    }

    public function scopeType($query, $type)
    {
        return $query->when($type, fn($q) => $q->where('type', $type));
    }

    public function scopeCategory($query, $category)
    {
        return $query->when($category, fn($q) => $q->where('category', $category));
    }

    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        return $query
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice));
    }

    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->when($bedrooms, fn($q) => $q->where('bedrooms', '>=', (int) $bedrooms));
    }

    public function scopeMinBathrooms($query, $bathrooms)
    {
        return $query->when($bathrooms, fn($q) => $q->where('bathrooms', '>=', (int) $bathrooms));
    }

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

    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->orderBy('price', $direction);
    }

    public function scopeResidential($query)
    {
        return $query->where('category', 'residential');
    }

    public function scopeCommercial($query)
    {
        return $query->where('category', 'commercial');
    }

    public function scopeIndustrial($query)
    {
        return $query->where('category', 'industrial');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedPriceAttribute()
    {
        $prefix = $this->purpose === 'rent' ? 'Rs/month' : 'Rs';
        return $prefix . ' ' . number_format($this->price);
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/images/' . $this->image)
            : asset('storage/images/image1.jpg');
    }
    // public function getImageUrlAttribute()
    // {
    //     if ($this->image && file_exists(public_path('storage/images/' . $this->image))) {
    //         return asset('storage/images/' . $this->image);
    //     }

    //     return asset('storage/images/image1.jpg');
    // }


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

    public function getPurposeLabelAttribute()
    {
        return $this->purpose === 'rent' ? 'For Rent' : 'For Sale';
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
        ];

        return $labels[$this->category] ?? $this->category;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'approved';
    }

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
