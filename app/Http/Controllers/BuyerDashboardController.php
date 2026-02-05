<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class BuyerDashboardController extends Controller
{
    /**
     * Display the buyer's dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get stats for dashboard
        $stats = [
            'total_properties' => Property::approved()->count(),
            'for_sale' => Property::approved()->where('purpose', 'buy')->count(),
            'for_rent' => Property::approved()->where('purpose', 'rent')->count(),
            'avg_price' => Property::approved()->avg('price'),
        ];

        // Get featured/recent properties
        $recentProperties = Property::approved()->latest()->take(6)->get();

        return view('buyer.dashboard', compact('stats', 'recentProperties'));
    }
}

