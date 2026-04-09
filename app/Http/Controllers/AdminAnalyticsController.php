<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Enquiry;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // 1. User Growth (Last 30 Days)
        $userGrowth = User::where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Conversion Data
        $totalViews = DB::table('property_views')->count();
        $totalEnquiries = Enquiry::count();
        $conversionRate = $totalViews > 0 ? ($totalEnquiries / $totalViews) * 100 : 0;

        // 3. Demand by Type
        $demandByType = Enquiry::join('properties', 'enquiries.property_id', '=', 'properties.id')
            ->select('properties.type', DB::raw('count(*) as count'))
            ->groupBy('properties.type')
            ->orderBy('count', 'desc')
            ->get();

        // 4. Recommendation Accuracy Over Time
        $matchTrends = Enquiry::whereNotNull('match_score')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(match_score) as avg_score'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 5. Seller Activity
        $topSellers = User::where('role', 'owner')
            ->withCount('properties')
            ->orderBy('properties_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.analytics', compact(
            'userGrowth',
            'totalViews',
            'totalEnquiries',
            'conversionRate',
            'demandByType',
            'matchTrends',
            'topSellers'
        ));
    }
}
