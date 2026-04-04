<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class FavoriteController extends Controller
{
    public function favorites()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->with('property')->get();

        return view('buyer.favorites', compact('favorites'));
    }
    
    /**
     * Toggle favorite status for a property
     */
    public function toggle(Request $request, Property $property)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Please login first',
                'is_favorite' => false
            ], 401);
        }
        
        // Check if already favorited
        $existingFavorite = $user->favorites()->where('property_id', $property->id)->first();
        
        if ($existingFavorite) {
            // Remove from favorites
            $existingFavorite->delete();
            $isFavorite = false;
            $message = 'Removed from favorites';
        } else {
            // Add to favorites
            $user->favorites()->create([
                'property_id' => $property->id
            ]);
            $isFavorite = true;
            $message = 'Added to favorites';
        }
        
        return response()->json([
            'message' => $message,
            'is_favorite' => $isFavorite,
            'count' => $user->favorites()->count()
        ]);
    }
    
    /**
     * Check if property is favorited (optional endpoint)
     */
    public function check(Property $property)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['is_favorite' => false]);
        }
        
        $isFavorite = $user->favorites()->where('property_id', $property->id)->exists();
        
        return response()->json(['is_favorite' => $isFavorite]);
    }
}