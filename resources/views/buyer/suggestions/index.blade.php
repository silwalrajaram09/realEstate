<x-app-layout>

<style>
    .sugg-root { font-family: 'Outfit', sans-serif; }

    /* ── Header ── */
    .sugg-header {
        position: relative;
        overflow: hidden;
        background: #0f0f0f;
        border-radius: 4px;
        padding: 3rem 3.5rem;
        margin-bottom: 2.5rem;
    }
    .sugg-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(201,169,110,0.06) 39px, rgba(201,169,110,0.06) 40px),
            repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(201,169,110,0.06) 39px, rgba(201,169,110,0.06) 40px);
        pointer-events: none;
    }
    .sugg-header::after {
        content: '';
        position: absolute;
        top: -4rem; right: -4rem;
        width: 18rem; height: 18rem;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .sugg-header-eyebrow {
        display: flex; align-items: center; gap: 0.625rem;
        margin-bottom: 0.75rem;
    }
    .sugg-header-eyebrow-line { width: 1.5rem; height: 1px; background: #c9a96e; }
    .sugg-header-eyebrow span {
        font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase;
        color: #c9a96e; font-weight: 600;
    }
    .sugg-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 600;
        color: #fff;
        line-height: 1.1;
        margin-bottom: 0.625rem;
        position: relative;
    }
    .sugg-header h1 em { color: #c9a96e; font-style: italic; }
    .sugg-header p {
        font-size: 0.875rem; font-weight: 300;
        color: rgba(255,255,255,0.5);
        max-width: 36rem;
        position: relative;
    }
    .sugg-header-meta {
        position: absolute; top: 2.5rem; right: 3.5rem;
        display: flex; flex-direction: column; align-items: flex-end; gap: 0.375rem;
    }
    .sugg-header-count {
        font-family: 'Cormorant Garamond', serif;
        font-size: 3.5rem; font-weight: 300; line-height: 1;
        color: rgba(201,169,110,0.25);
        letter-spacing: -0.02em;
    }
    .sugg-header-count-label {
        font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase;
        color: rgba(255,255,255,0.3); font-weight: 500;
    }

    /* ── Toolbar ── */
    .sugg-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        margin-bottom: 1.75rem;
    }
    .sugg-tabs {
        display: flex; gap: 0;
        border: 1px solid #ede8df;
        border-radius: 3px;
        overflow: hidden;
        background: #fff;
    }
    .sugg-tab {
        padding: 0.45rem 1rem;
        font-size: 0.72rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        color: #8c8070;
        background: transparent;
        border: none;
        border-right: 1px solid #ede8df;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
        display: flex; align-items: center; gap: 0.4rem;
    }
    .sugg-tab:last-child { border-right: none; }
    .sugg-tab.active, .sugg-tab:hover {
        background: #c9a96e;
        color: #0f0f0f;
    }
    .sugg-sort {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem; font-weight: 500; color: #3a3028;
        background: #fff;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.45rem 2rem 0.45rem 0.75rem;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.625rem center;
        cursor: pointer;
    }
    .sugg-sort:focus { outline: none; border-color: #c9a96e; }

    /* ── Cards ── */
    .prop-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        overflow: hidden;
        transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
        display: flex; flex-direction: column;
    }
    .prop-card:hover {
        border-color: #c9a96e;
        transform: translateY(-3px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.07);
    }
    .prop-img {
        position: relative;
        height: 13rem;
        background: #f5f0e8;
        overflow: hidden;
    }
    .prop-img img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .prop-card:hover .prop-img img { transform: scale(1.04); }
    .prop-img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
    }
    .prop-badge {
        position: absolute; top: 0.75rem; left: 0.75rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        border-radius: 2px;
    }
    .prop-badge-purpose {
        background: #c9a96e; color: #0f0f0f;
    }
    .prop-badge-type {
        position: absolute; top: 0.75rem; right: 0.75rem;
        background: rgba(255,255,255,0.92); color: #4a4038;
        padding: 0.25rem 0.625rem;
        font-size: 0.62rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        border-radius: 2px;
    }
    .prop-score-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        height: 2px;
        background: rgba(0,0,0,0.08);
    }
    .prop-score-fill {
        height: 100%;
        background: #c9a96e;
        transition: width 0.6s ease;
    }
    .prop-body { padding: 1.25rem 1.375rem; flex: 1; display: flex; flex-direction: column; }
    .prop-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem; font-weight: 600; color: #0f0f0f;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .prop-loc {
        display: flex; align-items: center; gap: 0.375rem;
        font-size: 0.78rem; color: #8c8070; font-weight: 300;
        margin-bottom: 0.875rem;
    }
    .prop-specs {
        display: flex; align-items: center; gap: 1rem;
        font-size: 0.75rem; color: #6b5e52; font-weight: 400;
        padding: 0.75rem 0;
        border-top: 1px solid #f0ece4;
        border-bottom: 1px solid #f0ece4;
        margin-bottom: 0.875rem;
    }
    .prop-spec { display: flex; align-items: center; gap: 0.3rem; }
    .prop-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
    .prop-price {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem; font-weight: 600; color: #0f0f0f;
        line-height: 1;
    }
    .prop-price span { font-size: 0.7rem; font-weight: 400; color: #8c8070; display: block; margin-top: 0.2rem; }
    .prop-btn {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.55rem 1rem;
        background: #0f0f0f; color: #fff;
        font-family: 'Outfit', sans-serif;
        font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        border-radius: 2px; text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .prop-btn:hover { background: #c9a96e; color: #0f0f0f; }

    /* ── Empty state ── */
    .empty-state {
        grid-column: 1 / -1;
        display: flex; flex-direction: column; align-items: center;
        text-align: center;
        padding: 5rem 2rem;
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
    }
    .empty-icon {
        width: 5rem; height: 5rem;
        border-radius: 50%;
        background: rgba(201,169,110,0.07);
        border: 1px solid rgba(201,169,110,0.2);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.5rem;
    }
    .empty-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.75rem; font-weight: 600; color: #0f0f0f;
        margin-bottom: 0.625rem;
    }
    .empty-sub {
        font-size: 0.875rem; font-weight: 300; color: #8c8070;
        max-width: 26rem; margin: 0 auto 2rem;
        line-height: 1.7;
    }
    .empty-steps {
        display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5rem;
        margin-bottom: 2.5rem;
        text-align: left;
    }
    .empty-step {
        display: flex; align-items: flex-start; gap: 0.75rem;
        max-width: 14rem;
    }
    .empty-step-num {
        flex-shrink: 0;
        width: 1.5rem; height: 1.5rem;
        border-radius: 50%;
        border: 1px solid #c9a96e;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; font-weight: 700; color: #c9a96e;
    }
    .empty-step-text { font-size: 0.8rem; color: #6b5e52; line-height: 1.5; }
    .empty-step-text strong { display: block; color: #0f0f0f; font-weight: 600; margin-bottom: 0.125rem; }
    .empty-cta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.8rem 2rem;
        background: #c9a96e; color: #0f0f0f;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
        border-radius: 2px; text-decoration: none;
        transition: background 0.2s;
    }
    .empty-cta:hover { background: #b5924f; }

    /* ── Cosine badge ── */
    .cosine-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.625rem;
        background: rgba(201,169,110,0.08);
        border: 1px solid rgba(201,169,110,0.25);
        border-radius: 2px;
        font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        color: #9a7340;
    }
    .cosine-dot {
        width: 6px; height: 6px; border-radius: 50%; background: #c9a96e;
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

    /* ── Fade-in animation ── */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
    .prop-card { animation: fadeUp 0.4s ease both; }
</style>

<div class="sugg-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── HEADER ── --}}
    <div class="sugg-header">
        <div class="sugg-header-eyebrow">
            <div class="sugg-header-eyebrow-line"></div>
            <span>For you</span>
        </div>
        <h1>Your <em>Suggestions</em></h1>
        <p>Properties matched to your preferences using cosine similarity — the closer the match, the higher it ranks.</p>

        @if(isset($properties))
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
                Showing {{ $properties->count() }} personalised results
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

                    {{-- Image --}}
                    <div class="prop-img">
                        @if($property->image)
                            <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}">
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

                        {{-- Cosine score bar at bottom of image --}}
                        @if(isset($property->similarity_score))
                            <div class="prop-score-bar">
                                <div class="prop-score-fill" style="width: {{ round($property->similarity_score * 100) }}%"></div>
                            </div>
                        @endif
                    </div>

                    {{-- Body --}}
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
                                <span>{{ ucfirst($property->category ?? '') }}</span>
                            </div>
                            <a href="{{ route('buyer.properties.show', $property->id) }}" class="prop-btn">
                                View
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
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

    {{-- ── PAGINATION ── --}}
    @if(isset($properties) && $properties instanceof \Illuminate\Pagination\AbstractPaginator && $properties->hasPages())
        <div style="margin-top: 3rem;">
            {{ $properties->links() }}
        </div>
    @endif

</div>

</x-app-layout>