<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\PropertyView;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class BuyerDashboardController extends Controller
{
    public function __construct(private readonly PropertyService $properties) {}

    public function index(Request $request)
    {
        $user = $request->user();

        return view('buyer.dashboard', [
            'stats'                    => $this->properties->stats(),
            'userStats'                => $this->userStats($user),
            'recentlyViewed'           => $this->recentlyViewed($user),
            'personalizedRecommendations' => $this->properties->personalized($user),
            'trendingProperties'       => Property::approved()->orderByDesc('views_count')->limit(6)->get(),
            'newListings'              => Property::approved()->latest()->limit(6)->get(),
            'propertyTypes'            => $this->propertyTypeCounts(),
            'nearbyProperties'         => $this->nearbyForUser($user),
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function userStats(?object $user): array
    {
        if (! $user) {
            return [];
        }

        return [
            'total_views'     => PropertyView::where('user_id', $user->id)->count(),
            'total_favorites' => Favorite::where('user_id', $user->id)->count(),
        ];
    }

    private function recentlyViewed(?object $user): iterable
    {
        if ($user) {
            return Property::join('property_views', 'properties.id', '=', 'property_views.property_id')
                ->where('property_views.user_id', $user->id)
                ->where('properties.status', 'approved')
                ->orderByDesc('property_views.created_at')
                ->select('properties.*')
                ->distinct()
                ->limit(6)
                ->get();
        }

        $ids = session('recently_viewed', []);

        if (empty($ids)) {
            return collect();
        }

        return Property::whereIn('id', $ids)
            ->approved()
            ->get()
            ->sortBy(fn ($p) => array_search($p->id, $ids));
    }

    private function propertyTypeCounts(): array
    {
        return [
            'apartments' => Property::approved()->where('type', 'flat')->count(),
            'houses'     => Property::approved()->where('type', 'house')->count(),
            'land'       => Property::approved()->where('type', 'land')->count(),
            'commercial' => Property::approved()->where('type', 'commercial')->count(),
        ];
    }

    private function nearbyForUser(?object $user): iterable
    {
        if ($user?->latitude && $user?->longitude) {
            return $this->properties->nearby($user->latitude, $user->longitude, 10)->take(6);
        }

        return collect();
    }
}