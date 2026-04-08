<x-app-layout>

<link rel="stylesheet" href="{{ asset('css/suggestion.css') }}">

<div class="sugg-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── HEADER ── --}}
    <div class="sugg-header">
        <div class="sugg-header-eyebrow">
            <div class="sugg-header-eyebrow-line"></div>
            {{--
                FIX: Show the strategy label passed from the controller
                so the user understands HOW these were chosen.
                e.g. "Personalised for you" / "Based on your browsing history"
            --}}
            <span>{{ $strategyLabel ?? 'For you' }}</span>
        </div>
        <h1>Your <em>Suggestions</em></h1>
        <p>Properties matched to your preferences using cosine similarity — the closer the match, the higher it ranks.</p>

        {{--
            FIX: $properties is now always a plain Collection (not a Paginator)
            because personalized($prefs, $limit) returns a Collection.
            So we use ->count() only — no ->total() needed.
        --}}
        @if(isset($properties) && $properties->count() > 0)
            <div class="sugg-header-meta">
                <div class="sugg-header-count">{{ $properties->count() }}</div>
                <div class="sugg-header-count-label">matched listings</div>
            </div>
        @endif
    </div>

    {{-- ── TOOLBAR ── --}}
    @if(isset($properties) && $properties->count() > 0)
    <div class="sugg-toolbar">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <div class="cosine-badge">
                <div class="cosine-dot"></div>
                Cosine similarity ranked
            </div>
            <span style="font-size:0.8rem; font-weight:300; color:#8c8070;">
                Showing {{ $properties->count() }} personalised result{{ $properties->count() === 1 ? '' : 's' }}
            </span>
        </div>
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <a href="{{ route('buyer.properties') }}"
               style="font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;
                      color:#8c8070; text-decoration:none; padding:0.45rem 0.875rem;
                      border:1px solid #ede8df; border-radius:3px; background:#fff;
                      transition: border-color 0.2s, color 0.2s;"
               onmouseover="this.style.borderColor='#c9a96e';this.style.color='#9a7340'"
               onmouseout="this.style.borderColor='#ede8df';this.style.color='#8c8070'">
                Browse all
            </a>
        </div>
    </div>
    @endif

    {{-- ── GRID ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @if(isset($properties) && $properties->count() > 0)
            @foreach($properties as $i => $property)
                <div class="prop-card" style="animation-delay: {{ $i * 0.06 }}s">

                    {{-- ── Image ── --}}
                    <div class="prop-img">
                        @if($property->image_url)
                            <img src="{{ $property->image_url }}" alt="{{ $property->title }}">
                        @else
                            <div class="prop-img-placeholder">
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                        @endif

                        <span class="prop-badge prop-badge-purpose">{{ ucfirst($property->purpose) }}</span>
                        <span class="prop-badge-type">{{ ucfirst($property->type) }}</span>

                        {{-- Score display removed, we will use reason pills below instead --}}
                    </div>

                    {{-- ── Body ── --}}
                    <div class="prop-body">
                        <div class="prop-title">{{ $property->title }}</div>

                        <div class="prop-loc">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $property->location }}
                        </div>

                        <div class="prop-specs">
                            @if($property->bedrooms)
                                <div class="prop-spec">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 12h18M3 12V8a2 2 0 012-2h14a2 2 0 012 2v4M3 12v5a1 1 0 001 1h16a1 1 0 001-1v-5"/>
                                    </svg>
                                    {{ $property->bedrooms }} bed
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="prop-spec">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 12h16v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5zM4 12V8a4 4 0 014-4h1"/>
                                    </svg>
                                    {{ $property->bathrooms }} bath
                                </div>
                            @endif
                            @if($property->area)
                                <div class="prop-spec">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    {{ number_format($property->area) }} sq.ft
                                </div>
                            @endif
                        </div>

                        <div class="prop-footer">
                            <div class="prop-price">
                                Rs {{ number_format($property->price) }}
                                {{-- FIX: guard against null category with @if --}}
                                @if($property->category)
                                    <span>{{ ucfirst($property->category) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('buyer.properties.show', $property->id) }}" class="prop-btn">
                                View
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                        {{--
                            HYBRID RECOMMENDATION REASONS
                        --}}
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.65rem;">
                            @if(isset($property->hybrid_score))
                                <div class="prop-match-pill" style="background:#fdf6ec; color:#9a7340; border-color:#f0e0c0;">
                                    ⭐ {{ round($property->hybrid_score * 100) }}% Hybrid
                                </div>
                            @endif
                            @if(isset($property->hybrid_content_score) && $property->hybrid_content_score > 0.3)
                                <div class="prop-match-pill" style="background:#e2e8f0; color:#4a5568; border-color:#cbd5e1;">
                                    🎯 Content Match
                                </div>
                            @endif
                            @if(isset($property->hybrid_collab_score) && $property->hybrid_collab_score > 0.3)
                                <div class="prop-match-pill" style="background:#ebf8ff; color:#2b6cb0; border-color:#bee3f8;">
                                    👥 Users Like You
                                </div>
                            @endif
                            @if(isset($property->hybrid_pop_score) && $property->hybrid_pop_score > 0.3)
                                <div class="prop-match-pill" style="background:#fff5f5; color:#c53030; border-color:#fed7d7;">
                                    🔥 Trending
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach

        @else
            {{-- ── EMPTY STATE ── --}}
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.25">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>

                <div class="empty-title">No Suggestions Yet</div>
                <p class="empty-sub">
                    The cosine similarity engine needs a signal from you. Browse or favourite a few properties and it will learn what to recommend.
                </p>

                <div class="empty-steps">
                    <div class="empty-step">
                        <div class="empty-step-num">1</div>
                        <div class="empty-step-text">
                            <strong>Browse properties</strong>
                            View listings that interest you — each view is a signal.
                        </div>
                    </div>
                    <div class="empty-step">
                        <div class="empty-step-num">2</div>
                        <div class="empty-step-text">
                            <strong>Save favourites</strong>
                            The algorithm weights your saved properties most heavily.
                        </div>
                    </div>
                    <div class="empty-step">
                        <div class="empty-step-num">3</div>
                        <div class="empty-step-text">
                            <strong>Get matched</strong>
                            Cosine similarity finds listings closest to your preference vector.
                        </div>
                    </div>
                </div>

                <a href="{{ route('buyer.properties') }}" class="empty-cta">
                    Start browsing
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif

    </div>

    {{--
        PAGINATION REMOVED:
        personalized($preferences, $limit) returns a plain Collection, not a
        LengthAwarePaginator. The limit is 12, so there is nothing to paginate.
        If you later switch the service to return a Paginator, add it back.
    --}}

</div>

{{--
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ADD TO suggestion.css
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

.prop-match-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.65rem;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    color: #9a7340;
    background: #fdf6ec;
    border: 1px solid #f0e0c0;
    border-radius: 20px;
    padding: 0.25rem 0.6rem;
}
--}}

</x-app-layout>