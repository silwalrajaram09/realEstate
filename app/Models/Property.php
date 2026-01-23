<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
