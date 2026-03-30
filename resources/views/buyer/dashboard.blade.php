<x-app-layout>
<style>
    .dash-root { font-family: 'Outfit', sans-serif; }

    /* Welcome banner */
    .welcome-banner {
        background: #0f0f0f;
        border-radius: 4px;
        padding: 2.25rem 2.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .welcome-banner::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(to right, #c9a96e, transparent);
    }
    .welcome-banner::after {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(201,169,110,0.07) 0%, transparent 60%);
    }
    .welcome-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(1.5rem, 3vw, 2.25rem);
        font-weight: 600;
        color: #fff;
        line-height: 1.1;
    }
    .welcome-name em { color: #c9a96e; font-style: italic; }
    .welcome-sub {
        font-size: 0.875rem; font-weight: 300;
        color: rgba(255,255,255,0.5);
        margin-top: 0.375rem;
    }
    .welcome-badge {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.5rem 1.125rem;
        background: rgba(201,169,110,0.12);
        border: 1px solid rgba(201,169,110,0.3);
        border-radius: 2px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #c9a96e;
        letter-spacing: 0.06em;
    }

    /* Stat cards */
    .stat-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        padding: 1.5rem;
        transition: box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.06); border-color: #ddd5c8; }
    .stat-icon {
        width: 2.75rem; height: 2.75rem;
        border-radius: 3px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(201,169,110,0.1);
        border: 1px solid rgba(201,169,110,0.2);
        flex-shrink: 0;
    }
    .stat-number {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2rem;
        font-weight: 600;
        color: #0f0f0f;
        line-height: 1;
        margin: 0.5rem 0 0.25rem;
    }
    .stat-label {
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #8c8070;
        font-weight: 500;
    }
    .stat-sub {
        font-size: 0.775rem;
        color: #b0a090;
        font-weight: 300;
        margin-top: 0.5rem;
    }
    .stat-sub strong { color: #c9a96e; font-weight: 600; }

    /* Section panels */
    .panel {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 1.75rem;
    }
    .panel-head {
        padding: 1.375rem 1.75rem;
        border-bottom: 1px solid #f4ede3;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;
    }
    .panel-eyebrow {
        font-size: 0.62rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #c9a96e;
        font-weight: 600;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .panel-eyebrow::before { content: ''; display: block; width: 1rem; height: 1px; background: #c9a96e; }
    .panel-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #0f0f0f;
        margin-top: 0.2rem;
    }
    .panel-link {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #0f0f0f;
        text-decoration: none;
        border-bottom: 1.5px solid #c9a96e;
        padding-bottom: 2px;
        transition: color 0.2s ease;
        white-space: nowrap;
    }
    .panel-link:hover { color: #c9a96e; }
    .panel-body { padding: 1.75rem; }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 4rem 2rem;
    }
    .empty-icon {
        width: 4rem; height: 4rem;
        border-radius: 50%;
        background: rgba(201,169,110,0.08);
        border: 1px solid rgba(201,169,110,0.2);
        display: inline-flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem;
    }
    .empty-msg {
        font-size: 0.875rem; font-weight: 300; color: #8c8070;
        max-width: 22rem; margin: 0 auto;
    }

    /* Gold separator */
    .gold-rule { height: 1px; background: linear-gradient(to right, #c9a96e, transparent); border: none; margin: 0.5rem 0 1.75rem; }
</style>

<div class="dash-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner reveal">
        <div style="position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <p class="welcome-sub">Good to see you back</p>
                <h1 class="welcome-name">
                    Welcome, <em>{{ Auth::user()->name }}</em>
                </h1>
                <p class="welcome-sub" style="margin-top:0.5rem;">
                    Discover your perfect home from {{ number_format($stats['total_properties'] ?? 0) }}+ verified listings
                </p>
            </div>
            <div class="welcome-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ $stats['new_today'] ?? 0 }} new today
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="stat-card reveal reveal-delay-1">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div class="stat-number">{{ number_format($stats['total_properties'] ?? 0) }}</div>
            <div class="stat-label">Total Listings</div>
            <div class="stat-sub">
                <strong>{{ number_format($stats['for_sale'] ?? 0) }}</strong> for sale &nbsp;·&nbsp;
                <strong>{{ number_format($stats['for_rent'] ?? 0) }}</strong> for rent
            </div>
        </div>

        <div class="stat-card reveal reveal-delay-2">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-number">Rs {{ number_format(($stats['avg_price'] ?? 0) / 1000) }}K</div>
            <div class="stat-label">Average Price</div>
            <div class="stat-sub">Across all active listings</div>
        </div>

        <div class="stat-card reveal reveal-delay-3">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
            <div class="stat-number">{{ $userStats['total_views'] ?? 0 }}</div>
            <div class="stat-label">Your Views</div>
            <div class="stat-sub"><strong>{{ $userStats['total_favorites'] ?? 0 }}</strong> saved properties</div>
        </div>

        <div class="stat-card reveal reveal-delay-3">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="stat-number">{{ number_format(($propertyTypes['apartments'] ?? 0) + ($propertyTypes['houses'] ?? 0)) }}</div>
            <div class="stat-label">Residential</div>
            <div class="stat-sub">
                <strong>{{ number_format($propertyTypes['apartments'] ?? 0) }}</strong> apartments &nbsp;·&nbsp;
                <strong>{{ number_format($propertyTypes['houses'] ?? 0) }}</strong> houses
            </div>
        </div>

    </div>

    <hr class="gold-rule">

    {{-- ── RECOMMENDED FOR YOU ── --}}
    @if(isset($personalizedRecommendations) && $personalizedRecommendations->count() > 0)
        <div class="panel reveal">
            <div class="panel-head">
                <div>
                    <div class="panel-eyebrow">AI Picks</div>
                    <div class="panel-title">Recommended For You</div>
                </div>
                <a href="{{ route('buyer.suggestions') }}" class="panel-link">View All →</a>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($personalizedRecommendations as $property)
                        <x-property-card :property="$property" :showScore="true" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── NEAR YOU ── --}}
    @if(isset($nearbyProperties) && $nearbyProperties->count() > 0)
        <div class="panel reveal">
            <div class="panel-head">
                <div>
                    <div class="panel-eyebrow">Location</div>
                    <div class="panel-title">Near You</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($nearbyProperties as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ── RECENTLY VIEWED ── --}}
    {{-- @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
        <div class="panel reveal">
            <div class="panel-head">
                <div>
                    <div class="panel-eyebrow">History</div>
                    <div class="panel-title">Recently Viewed</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentlyViewed as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

    {{-- ── TRENDING ── --}}
    {{-- @if(isset($trendingProperties) && $trendingProperties->count() > 0)
        <div class="panel reveal">
            <div class="panel-head">
                <div>
                    <div class="panel-eyebrow">Trending</div>
                    <div class="panel-title">Most Viewed This Week</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($trendingProperties as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

    {{-- ── NEW LISTINGS ── --}}
    {{-- @if(isset($newListings) && $newListings->count() > 0)
        <div class="panel reveal">
            <div class="panel-head">
                <div>
                    <div class="panel-eyebrow">Fresh</div>
                    <div class="panel-title">New Listings</div>
                </div>
                <a href="{{ route('buyer.properties') }}" class="panel-link">Browse All →</a>
            </div>
            <div class="panel-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($newListings as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

    {{-- ── EMPTY STATE ── --}}
    @if((!isset($newListings) || $newListings->isEmpty()) && (!isset($personalizedRecommendations) || $personalizedRecommendations->isEmpty()))
        <div class="panel reveal">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <p style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.5rem;">
                    No Listings Yet
                </p>
                <p class="empty-msg">Check back soon — new properties are added daily.</p>
                <a href="{{ route('buyer.properties') }}"
                   style="display:inline-flex; align-items:center; gap:0.5rem; margin-top:1.5rem;
                          padding:0.7rem 1.75rem; background:#c9a96e; color:#0f0f0f;
                          font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
                          border-radius:3px; text-decoration:none; transition:background 0.2s ease;"
                   onmouseover="this.style.background='#b5924f'" onmouseout="this.style.background='#c9a96e'">
                    Browse Properties
                </a>
            </div>
        </div>
    @endif

</div>
</x-app-layout>