<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'property_id',
        'buyer_id',
        'seller_id',
        'status',
        'reply',
        'replied_at',
        'match_score',
        'match_details',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'match_details' => 'array',
    ];

    /* ─── Relationships ─── */

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /* ─── Scopes ─── */

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeForBuyer($query, $buyerId)
    {
        return $query->where('buyer_id', $buyerId);
    }

    /* ─── Helpers ─── */

    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }
}