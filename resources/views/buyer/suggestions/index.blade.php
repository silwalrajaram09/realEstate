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
        @php
            $rankedProperties = collect($properties ?? [])->filter(function ($p) {
                return ($p->recommendation_confidence ?? 'medium') !== 'low';
            })->values();
        @endphp
        @if($rankedProperties->count() > 0)
            <div class="sugg-header-meta">
                <div class="sugg-header-count">{{ $rankedProperties->count() }}</div>
                <div class="sugg-header-count-label">matched listings</div>
            </div>
        @endif
    </div>

    {{-- ── TOOLBAR ── --}}
    @if(isset($rankedProperties) && $rankedProperties->count() > 0)
    <div class="sugg-toolbar">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <div class="cosine-badge">
                <div class="cosine-dot"></div>
                Cosine similarity ranked
            </div>
            <span style="font-size:0.8rem; font-weight:300; color:#8c8070;">
                Showing {{ $rankedProperties->count() }} personalised result{{ $rankedProperties->count() === 1 ? '' : 's' }}
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

        @if(isset($rankedProperties) && $rankedProperties->count() > 0)
            @foreach($rankedProperties as $i => $property)
                 <x-property-card :property="$property" :showScore="true" />
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