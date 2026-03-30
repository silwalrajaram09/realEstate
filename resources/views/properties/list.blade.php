<x-public>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,400&family=Outfit:wght@300;400;500;600&display=swap');

    .list-root { font-family: 'Outfit', sans-serif; }

    /* Page header */
    .list-eyebrow {
        font-size: 0.68rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #c9a96e;
        font-weight: 500;
    }
    .list-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 4vw, 2.75rem);
        font-weight: 600;
        color: #0f0f0f;
        line-height: 1.1;
    }

    /* Sort select */
    .sort-select {
        font-family: 'Outfit', sans-serif;
        font-size: 0.8rem;
        font-weight: 500;
        letter-spacing: 0.04em;
        color: #3a3028;
        background: #fff;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.55rem 2.25rem 0.55rem 0.875rem;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        cursor: pointer;
        transition: border-color 0.2s ease;
    }
    .sort-select:focus {
        outline: none;
        border-color: #c9a96e;
        box-shadow: 0 0 0 3px rgba(201,169,110,0.1);
    }

    /* Count badge */
    .count-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        background: rgba(201,169,110,0.1);
        border: 1px solid rgba(201,169,110,0.25);
        border-radius: 3px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #9a7340;
        letter-spacing: 0.04em;
    }

    /* Property card */
    .prop-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
    }
    .prop-card:hover {
        box-shadow: 0 16px 48px rgba(0,0,0,0.09);
        transform: translateY(-3px);
        border-color: transparent;
    }

    /* Image wrapper */
    .card-img-wrap {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #e8e0d4;
    }
    .card-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    .prop-card:hover .card-img-wrap img {
        transform: scale(1.05);
    }
    .card-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15,15,15,0.45) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .prop-card:hover .card-img-overlay { opacity: 1; }

    /* Badges */
    .badge-purpose {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 0.25rem 0.625rem;
        border-radius: 2px;
    }
    .badge-sale { background: #c9a96e; color: #0f0f0f; }
    .badge-rent { background: #0f0f0f; color: #c9a96e; }
    .badge-type {
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 0.25rem 0.625rem;
        border-radius: 2px;
        background: rgba(255,255,255,0.9);
        color: #3a3028;
        backdrop-filter: blur(4px);
    }

    /* Price on image */
    .card-price {
        position: absolute;
        bottom: 0.875rem;
        right: 0.875rem;
        background: rgba(250,247,242,0.97);
        backdrop-filter: blur(6px);
        border-radius: 3px;
        padding: 0.35rem 0.875rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f0f0f;
        line-height: 1.2;
    }
    .card-price .per-month {
        font-family: 'Outfit', sans-serif;
        font-size: 0.65rem;
        color: #8c8070;
        font-weight: 400;
        display: block;
        text-align: right;
    }

    /* Favorite button */
    .fav-btn {
        position: absolute;
        top: 0.875rem;
        right: 0.875rem;
        width: 2.125rem;
        height: 2.125rem;
        border-radius: 50%;
        background: rgba(255,255,255,0.92);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        opacity: 0;
        transition: opacity 0.25s ease, background 0.2s ease;
        backdrop-filter: blur(4px);
    }
    .prop-card:hover .fav-btn { opacity: 1; }
    .fav-btn:hover { background: #fff; }
    .fav-btn svg { transition: stroke 0.2s ease; }
    .fav-btn:hover svg { stroke: #c0392b; }

    /* Card body */
    .card-body {
        padding: 1.375rem 1.5rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #0f0f0f;
        line-height: 1.25;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .card-title:hover { color: #c9a96e; }

    .card-location {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.8rem;
        font-weight: 300;
        color: #8c8070;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-top: 0.2rem;
    }

    /* Specs row */
    .specs-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border: 1px solid #f0ece4;
        border-radius: 3px;
        overflow: hidden;
    }
    .spec-item {
        text-align: center;
        padding: 0.625rem 0.25rem;
        border-right: 1px solid #f0ece4;
    }
    .spec-item:last-child { border-right: none; }
    .spec-label {
        font-size: 0.6rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b0a090;
        font-weight: 500;
        display: block;
    }
    .spec-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1a1a1a;
        display: block;
        margin-top: 0.15rem;
    }

    /* Feature tags */
    .feature-tag {
        font-size: 0.68rem;
        font-weight: 500;
        color: #6b5e52;
        background: #f4ede3;
        padding: 0.2rem 0.6rem;
        border-radius: 2px;
        letter-spacing: 0.03em;
    }

    /* CTA button */
    .card-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.7rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #0f0f0f;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        margin-top: auto;
    }
    .card-cta:hover {
        background: #c9a96e;
        border-color: #c9a96e;
        color: #0f0f0f;
    }
    .card-cta svg { transition: transform 0.2s ease; }
    .card-cta:hover svg { transform: translateX(3px); }

    /* Divider line */
    .gold-rule {
        height: 1px;
        background: linear-gradient(to right, #c9a96e, transparent);
        border: none;
        margin: 0;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
        border: 1px solid #ede8df;
        border-radius: 4px;
        background: #faf7f2;
    }
    .empty-icon {
        width: 5rem; height: 5rem;
        border-radius: 50%;
        background: rgba(201,169,110,0.1);
        border: 1px solid rgba(201,169,110,0.25);
        display: inline-flex; align-items: center; justify-content: center;
        margin-bottom: 1.75rem;
    }
</style>

<div class="list-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-14">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                <span class="list-eyebrow">Properties</span>
            </div>
            <h1 class="list-title">
                Browse <em style="color:#c9a96e; font-style:italic;">Listings</em>
            </h1>
            <p style="font-size:0.875rem; font-weight:300; color:#8c8070; margin-top:0.5rem;">
                Discover your perfect property from our curated selection
            </p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            {{-- Sort --}}
            <form method="GET" action="{{ route('properties.list') }}">
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest"     {{ request('sort') == 'latest'     ? 'selected' : '' }}>Most Recent</option>
                    <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                    <option value="oldest"     {{ request('sort') == 'oldest'     ? 'selected' : '' }}>Oldest First</option>
                </select>
            </form>

            {{-- Count --}}
            <span class="count-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                {{ number_format($properties->total() ?? $properties->count()) }} properties
            </span>
        </div>
    </div>

    <hr class="gold-rule mb-12">

    @if($properties->count())

        {{-- ── GRID ── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($properties as $property)

                <article class="prop-card reveal">

                    {{-- Image --}}
                    <div class="card-img-wrap">
                        <img src="{{ $property->image_url }}" alt="{{ $property->title }}" loading="lazy">
                        <div class="card-img-overlay"></div>

                        {{-- Badges --}}
                        <div style="position:absolute; top:0.875rem; left:0.875rem; display:flex; gap:0.375rem; flex-wrap:wrap;">
                            <span class="badge-purpose {{ $property->purpose === 'sale' ? 'badge-sale' : 'badge-rent' }}">
                                {{ $property->purpose === 'sale' ? 'For Sale' : 'For Rent' }}
                            </span>
                            <span class="badge-type">{{ ucfirst($property->type) }}</span>
                        </div>

                        {{-- Price --}}
                        <div class="card-price">
                            Rs {{ number_format($property->price) }}
                            @if($property->purpose === 'rent')
                                <span class="per-month">/month</span>
                            @endif
                        </div>

                        {{-- Fav --}}
                        <button class="fav-btn" aria-label="Save property">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#3a3028" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="card-body">
                        <div>
                            <a href="{{ route('properties.show', $property->id) }}" class="card-title">
                                {{ $property->title }}
                            </a>
                            <div class="card-location">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $property->location }}</span>
                            </div>
                        </div>

                        {{-- Specs --}}
                        @if($property->bedrooms || $property->bathrooms || $property->area)
                            <div class="specs-row">
                                @if($property->bedrooms)
                                    <div class="spec-item">
                                        <span class="spec-label">Beds</span>
                                        <span class="spec-value">{{ $property->bedrooms }}</span>
                                    </div>
                                @endif
                                @if($property->bathrooms)
                                    <div class="spec-item">
                                        <span class="spec-label">Baths</span>
                                        <span class="spec-value">{{ $property->bathrooms }}</span>
                                    </div>
                                @endif
                                @if($property->area)
                                    <div class="spec-item">
                                        <span class="spec-label">Area</span>
                                        <span class="spec-value" style="font-size:0.825rem;">{{ number_format($property->area) }}<span style="font-size:0.6rem; color:#8c8070; font-weight:400;"> sqft</span></span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Feature tags --}}
                        @if($property->features && count($property->features) > 0)
                            <div style="display:flex; flex-wrap:wrap; gap:0.375rem;">
                                @foreach(array_slice($property->features, 0, 3) as $feature)
                                    <span class="feature-tag">{{ $feature }}</span>
                                @endforeach
                                @if(count($property->features) > 3)
                                    <span class="feature-tag">+{{ count($property->features) - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- CTA --}}
                        <a href="{{ route('properties.show', $property->id) }}" class="card-cta">
                            View Details
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>

                </article>

            @endforeach
        </div>

        {{-- ── PAGINATION ── --}}
        <div class="mt-16 flex justify-center">
            <div style="font-family:'Outfit',sans-serif;">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        </div>

    @else

        {{-- ── EMPTY STATE ── --}}
        <div class="empty-state">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.75rem; font-weight:600; color:#0f0f0f; margin-bottom:0.75rem;">
                No Properties Found
            </h3>
            <p style="font-size:0.9rem; font-weight:300; color:#8c8070; max-width:26rem; margin:0 auto 2.5rem;">
                We couldn't find any properties matching your criteria. Try adjusting your filters or browse all listings.
            </p>
            <div style="display:flex; align-items:center; justify-content:center; gap:1rem; flex-wrap:wrap;">
                <a href="{{ route('properties.list') }}"
                   style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem;
                          background:#c9a96e; color:#0f0f0f; font-size:0.78rem; font-weight:700;
                          letter-spacing:0.1em; text-transform:uppercase; border-radius:3px; text-decoration:none;
                          transition:background 0.2s ease;"
                   onmouseover="this.style.background='#b5924f'" onmouseout="this.style.background='#c9a96e'">
                    Reset Filters
                </a>
                <button onclick="history.back()"
                        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem;
                               border:1px solid #ddd5c8; color:#4a4038; font-size:0.78rem; font-weight:700;
                               letter-spacing:0.1em; text-transform:uppercase; border-radius:3px; cursor:pointer;
                               background:#fff; transition:border-color 0.2s ease, background 0.2s ease;"
                        onmouseover="this.style.background='#faf7f2'; this.style.borderColor='#c9a96e'"
                        onmouseout="this.style.background='#fff'; this.style.borderColor='#ddd5c8'">
                    Go Back
                </button>
            </div>
        </div>

    @endif

</div>

</x-public>