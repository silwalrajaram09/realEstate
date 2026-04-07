<?php

namespace App\Http\Controllers;

use App\Events\PropertyStatusChangedEvent;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Favorite;
use App\Models\Enquiry;
use App\Models\PropertyView;
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
            'featured_properties' => Property::where('is_featured', true)->count(),
        ];

        // Get recent properties pending review
        $pendingProperties = Property::where('status', 'pending')
            ->with('seller')
            ->latest()
            ->take(10)
            ->get();

        // Get recent users
        $recentUsers = User::latest()->take(5)->get();

        $monthlyListings = Property::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingProperties', 'recentUsers', 'monthlyListings'));
    }

    /**
     * Show all users (admin).
     */
    public function users(Request $request)
    {
        $users = User::query()
            ->latest()
            ->paginate(12);

        return view('admin.users', compact('users'));
    }

    /**
     * Show all properties (admin).
     */
    public function properties(Request $request)
    {
        $status = $request->string('status')->toString();

        $properties = Property::query()
            ->with('seller')
            ->when(in_array($status, ['pending', 'approved', 'rejected'], true), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(12);

        return view('admin.properties', compact('properties'));
    }

    /**
     * Show analytics dashboard (admin).
     */
    public function analytics(Request $request)
    {
        $months = (int) $request->integer('months', 12);
        $months = max(3, min($months, 24));

        $start = now()->startOfMonth()->subMonths($months - 1);

        $monthlyListingsRaw = Property::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyUsersRaw = User::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = collect(range(0, $months - 1))
            ->map(fn(int $i) => $start->copy()->addMonths($i)->format('Y-m'));

        $monthlyListings = $labels->map(fn(string $month) => (int) ($monthlyListingsRaw[$month] ?? 0));
        $monthlyUsers = $labels->map(fn(string $month) => (int) ($monthlyUsersRaw[$month] ?? 0));

        $statusBreakdown = Property::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $analytics = [
            'total_views' => (int) PropertyView::count(),
            'total_favorites' => (int) Favorite::count(),
            'total_enquiries' => (int) Enquiry::count(),
            'avg_views_per_property' => round((float) Property::avg('views_count'), 1),
            'approval_rate' => Property::count() > 0
                ? round((Property::where('status', 'approved')->count() / Property::count()) * 100, 1)
                : 0.0,
        ];

        return view('admin.analytics', [
            'months' => $months,
            'labels' => $labels->values(),
            'monthlyListings' => $monthlyListings->values(),
            'monthlyUsers' => $monthlyUsers->values(),
            'statusBreakdown' => [
                'approved' => (int) ($statusBreakdown['approved'] ?? 0),
                'pending' => (int) ($statusBreakdown['pending'] ?? 0),
                'rejected' => (int) ($statusBreakdown['rejected'] ?? 0),
            ],
            'statusSeries' => [
                (int) ($statusBreakdown['approved'] ?? 0),
                (int) ($statusBreakdown['pending'] ?? 0),
                (int) ($statusBreakdown['rejected'] ?? 0),
            ],
            'analytics' => $analytics,
        ]);
    }

    /**
     * Approve a property.
     */
    public function approveProperty(Property $property)
    {
        $property->update(['status' => 'approved', 'rejection_reason' => null]);
        event(new PropertyStatusChangedEvent($property->user_id, ['property_id' => $property->id, 'status' => 'approved']));
        return redirect()->back()->with('success', 'Property approved successfully!');
    }

    /**
     * Reject a property.
     */
    public function rejectProperty(Request $request, Property $property)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $property->update(['status' => 'rejected', 'rejection_reason' => $validated['reason']]);
        event(new PropertyStatusChangedEvent($property->user_id, [
            'property_id' => $property->id,
            'status' => 'rejected',
            'reason' => $validated['reason'],
        ]));

        return redirect()->back()->with('success', 'Property rejected!');
    }

    public function requestChanges(Request $request, Property $property)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $property->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'],
        ]);

        event(new PropertyStatusChangedEvent($property->user_id, [
            'property_id' => $property->id,
            'status' => 'changes_requested',
            'reason' => $validated['reason'],
        ]));

        return back()->with('success', 'Change request sent to seller.');
    }

    public function toggleFeatured(Property $property)
    {
        $property->update(['is_featured' => !$property->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    public function toggleSuspend(User $user)
    {
        /** @var User|null $actingAdmin */
        $actingAdmin = auth()->user();
        if ($actingAdmin && $actingAdmin->id === $user->id) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $user->update(['is_suspended' => !$user->is_suspended]);
        return back()->with('success', 'User status updated.');
    }

    /**
     * Make a user an admin.
     */
    public function makeAdmin(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('info', 'User is already an admin.');
        }

        $user->update(['role' => 'admin']);
        return redirect()->back()->with('success', 'User is now an admin!');
    }
}

