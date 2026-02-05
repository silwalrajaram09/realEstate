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
        'location',
        'bedrooms',
        'bathrooms',
        'area',
        'parking',
        'water',
        'user_id',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'area' => 'integer',
        'parking' => 'boolean',
        'water' => 'boolean',
    ];

    /**
     * Get the seller (user) that owns the property.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include approved properties.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
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
     * Get formatted price.
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rs ' . number_format($this->price);
    }

    /**
     * Get main image URL.
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('images/' . $this->image) : null;
    }
}
