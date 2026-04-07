<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyVector extends Model
{
    protected $fillable = [
        'property_id',
        'vector',
        'raw_vector',
        'normalized_at',
    ];

    protected $casts = [
        'vector' => 'array',
        'raw_vector' => 'array',
        'normalized_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getVectorArrayAttribute(): array
    {
        return $this->vector ?? [];
    }
}
