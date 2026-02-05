<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;

class SellerDashboardController extends Controller
{
    /**
     * Display the seller's dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get seller's properties with different statuses
        $properties = Property::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->latest()
            ->paginate(10);

        // Calculate stats
        $stats = [
            'total' => Property::where('user_id', $user->id)->count(),
            'approved' => Property::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending' => Property::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected' => Property::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('seller.dashboard', compact('properties', 'stats'));
    }
}

