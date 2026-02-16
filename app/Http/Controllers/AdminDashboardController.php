<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Admin;

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
        ];

        // Get recent properties pending review
        $pendingProperties = Property::where('status', 'pending')
            ->with('seller')
            ->latest()
            ->take(10)
            ->get();

        // Get recent users
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'pendingProperties', 'recentUsers'));
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

