<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Enquiry;
use App\Models\UserRecommendation;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(Request $request)
    {
        // Get overall stats
        $stats = [
            'total_users' => User::count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'approved_properties' => Property::where('status', 'approved')->count(),
            'rejected_properties' => Property::where('status', 'rejected')->count(),
            'total_sellers' => User::where('role', 'owner')->count(),
            'total_buyers' => User::where('role', 'customer')->count(),
            
            // Intelligence Stats
            'total_enquiries' => Enquiry::count(),
            'high_quality_leads' => Enquiry::where('match_score', '>', 0.7)->count(),
            'engine_cached_recs' => UserRecommendation::count(),
        ];

        // Get top locations for properties
        $topLocations = Property::select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        // Get recent properties pending review
        $pendingProperties = Property::where('status', 'pending')
            ->with('seller')
            ->latest()
            ->take(10)
            ->get();

        // Get recent users
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'pendingProperties', 'recentUsers', 'topLocations'));
    }

    /**
     * Show all users (admin).
     */
    public function users(Request $request)
    {
        $users = User::latest()->paginate(12);
        return view('admin.users', compact('users'));
    }

    /**
     * Show all properties (admin).
     */
    public function properties(Request $request)
    {
        $properties = Property::with('seller')->latest()->paginate(12);
        return view('admin.properties', compact('properties'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Approve a property.
     */
    public function approveProperty(Property $property)
    {
        $property->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Property approved successfully!');
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(Request $request, Property $property)
    {
        $property->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Property rejected!');
    }

    /**
     * Make a user an admin.
     */
    public function makeAdmin(User $user)
    {
        $user->update(['role' => 'admin']);
        return redirect()->back()->with('success', 'User is now an admin!');
    }
}

