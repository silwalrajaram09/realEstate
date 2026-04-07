<x-public>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600&display=swap');

    .show-root { font-family: 'Outfit', sans-serif; -webkit-font-smoothing: antialiased; }

    /* Hero */
    .prop-hero {
        position: relative;
        height: 70vh;
        min-height: 520px;
        width: 100%;
        background: #1a1510;
        overflow: hidden;
    }
    .prop-hero img {
        width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.85;
    }
    .prop-hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(10,8,5,0.88) 0%, rgba(10,8,5,0.3) 45%, rgba(10,8,5,0.1) 100%);
    }
    /* Gold left accent */
    .prop-hero::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, transparent, #c9a96e 30%, #c9a96e 70%, transparent);
        z-index: 10;
    }

    /* Back button */
    .back-btn {
        position: absolute;
        top: 1.75rem;
        left: 2rem;
        z-index: 20;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 2px;
        backdrop-filter: blur(6px);
        background: rgba(255,255,255,0.06);
        transition: background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
    }
    .back-btn:hover {
        background: rgba(201,169,110,0.2);
        border-color: rgba(201,169,110,0.5);
        color: #fff;
    }

    /* Hero content */
    .hero-content {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        z-index: 10;
        padding: 3rem 2rem 2.5rem;
    }

    .hero-badge {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 0.3rem 0.75rem;
        border-radius: 2px;
        display: inline-block;
    }
    .hero-badge-sale { background: #c9a96e; color: #0f0f0f; }
    .hero-badge-rent { background: #0f0f0f; color: #c9a96e; border: 1px solid #c9a96e; }
    .hero-badge-ghost {
        background: rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.85);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .hero-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 4.5vw, 3.25rem);
        font-weight: 600;
        color: #fff;
        line-height: 1.1;
        margin: 0.875rem 0 0.625rem;
    }
    .hero-location {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.9rem;
        font-weight: 300;
        color: rgba(255,255,255,0.65);
    }

    /* Gallery thumbnails */
    .gallery-bar {
        display: flex;
        gap: 0.625rem;
        padding: 0.875rem 2rem;
        background: #0f0f0f;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .gallery-bar::-webkit-scrollbar { display: none; }
    .gallery-thumb {
        width: 4.5rem;
        height: 3.5rem;
        flex-shrink: 0;
        border-radius: 2px;
        overflow: hidden;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: border-color 0.2s ease;
        opacity: 0.6;
        transition: opacity 0.2s ease, border-color 0.2s ease;
    }
    .gallery-thumb:hover, .gallery-thumb.active {
        border-color: #c9a96e;
        opacity: 1;
    }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* Layout */
    .content-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 3.5rem 2rem 5rem;
    }

    /* Cards */
    .detail-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        padding: 2rem;
    }
    .card-section-label {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 0.68rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #c9a96e;
        font-weight: 600;
        margin-bottom: 1.375rem;
    }
    .card-section-label::before {
        content: '';
        display: block;
        width: 1.25rem;
        height: 1px;
        background: #c9a96e;
        flex-shrink: 0;
    }
    .card-heading {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f0f0f;
        margin-bottom: 1.25rem;
    }

    /* Feature grid */
    .feat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .feat-grid { grid-template-columns: repeat(3, 1fr); }
    }

    .feat-item {
        border: 1px solid #f0ece4;
        border-radius: 3px;
        padding: 1rem 0.75rem;
        text-align: center;
        background: #faf7f2;
    }
    .feat-icon {
        font-size: 1.5rem;
        margin-bottom: 0.375rem;
        display: block;
    }
    .feat-label {
        font-size: 0.6rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b0a090;
        font-weight: 500;
        display: block;
    }
    .feat-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #0f0f0f;
        display: block;
        margin-top: 0.2rem;
    }
    .feat-value.available { color: #5a8a5a; }

    /* Description */
    .prop-description {
        font-size: 0.9375rem;
        font-weight: 300;
        color: #4a4038;
        line-height: 1.85;
    }

    /* Amenity tags */
    .amenity-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.78rem;
        font-weight: 400;
        color: #4a4038;
        background: #f4ede3;
        border: 1px solid #ede0cd;
        padding: 0.35rem 0.75rem;
        border-radius: 2px;
    }
    .amenity-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #c9a96e;
        flex-shrink: 0;
    }

    /* Property details table */
    .detail-table { width: 100%; }
    .detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f4ede3;
        font-size: 0.875rem;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row-label { color: #8c8070; font-weight: 400; }
    .detail-row-value { font-weight: 500; color: #0f0f0f; }

    /* Sticky sidebar */
    .sidebar-sticky {
        position: sticky;
        top: 5.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Price card */
    .price-card {
        background: #0f0f0f;
        border-radius: 4px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    .price-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(to right, #c9a96e, transparent);
    }
    .price-label {
        font-size: 0.65rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.4);
        font-weight: 500;
    }
    .price-value {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.75rem;
        font-weight: 600;
        color: #c9a96e;
        line-height: 1;
        margin: 0.375rem 0 0.25rem;
    }
    .price-per {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.4);
        font-weight: 300;
    }
    .price-divider {
        height: 1px;
        background: rgba(255,255,255,0.08);
        margin: 1.5rem 0;
    }

    /* Contact form */
    .contact-heading {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 1.125rem;
    }
    .contact-input {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 3px;
        padding: 0.75rem 1rem;
        font-family: 'Outfit', sans-serif;
        font-size: 0.875rem;
        font-weight: 300;
        color: #fff;
        transition: border-color 0.2s ease;
        display: block;
        margin-bottom: 0.75rem;
        box-sizing: border-box;
    }
    .contact-input::placeholder { color: rgba(255,255,255,0.3); }
    .contact-input:focus {
        outline: none;
        border-color: #c9a96e;
        background: rgba(255,255,255,0.08);
    }
    .contact-btn {
        width: 100%;
        padding: 0.875rem;
        background: #c9a96e;
        color: #0f0f0f;
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-top: 0.25rem;
    }
    .contact-btn:hover { background: #b5924f; }

    /* Action buttons row */
    .action-row {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    .action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.625rem;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.65);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 3px;
        background: none;
        cursor: pointer;
        transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
    }
    .action-btn:hover {
        border-color: rgba(201,169,110,0.4);
        color: #c9a96e;
        background: rgba(201,169,110,0.06);
    }

    /* Property ID tag */
    .prop-id-tag {
        text-align: center;
        padding: 0.875rem;
        border: 1px solid #ede8df;
        border-radius: 3px;
        background: #faf7f2;
        font-size: 0.75rem;
        color: #8c8070;
        letter-spacing: 0.06em;
    }
    .prop-id-tag span {
        font-weight: 600;
        color: #4a4038;
    }

    /* Map */
    .map-placeholder {
        aspect-ratio: 16/6;
        background: #f4ede3;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #b0a090;
        font-size: 0.875rem;
        font-weight: 300;
        border: 1px solid #ede8df;
    }

    /* Section separator */
    .section-sep {
        height: 1px;
        background: linear-gradient(to right, #c9a96e, transparent);
        border: none;
        margin: 0.25rem 0 2rem;
    }
</style>

<div class="show-root">

    {{-- ── HERO ── --}}
    <div class="prop-hero">
        <img src="{{ $property->image_url ?? 'https://via.placeholder.com/1400x800/1a1510/c9a96e?text=No+Image' }}"
             alt="{{ $property->title }}">
        <div class="prop-hero-overlay"></div>

        {{-- Back --}}
        <a href="{{ route('properties.list') }}" class="back-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            All Listings
        </a>

        {{-- Hero content --}}
        <div class="hero-content">
            <div class="max-w-7xl mx-auto px-6 md:px-10">
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem; margin-bottom:0.875rem;">
                    <span class="hero-badge {{ $property->purpose === 'sale' ? 'hero-badge-sale' : 'hero-badge-rent' }}">
                        For {{ ucfirst($property->purpose) }}
                    </span>
                    <span class="hero-badge hero-badge-ghost">{{ ucfirst($property->type) }}</span>
                    @isset($property->category)
                        <span class="hero-badge hero-badge-ghost">{{ ucfirst($property->category) }}</span>
                    @endisset
                </div>

                <h1 class="hero-title">{{ $property->title }}</h1>

                <div class="hero-location">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $property->location }}
                </div>
            </div>
        </div>
    </div>

    {{-- ── GALLERY STRIP ── --}}
    @if($property->gallery ?? false)
        <div class="gallery-bar">
            @foreach($property->gallery as $i => $image)
                <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}">
                    <img src="{{ $image }}" alt="Gallery image {{ $i + 1 }}">
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── MAIN CONTENT ── --}}
    <div class="content-wrap show-root">
        <div style="display:grid; grid-template-columns:1fr; gap:2rem;">
            @php $lg = 'grid-template-columns: 2fr 1fr'; @endphp
            <div style="display:grid; gap:2rem; @media(min-width:1024px){ {{ $lg }} }">

            {{-- Trick: use flex row on large screens --}}
        </div>

        <div style="display:flex; flex-direction:column; gap:2rem; @media(min-width:1024px){flex-direction:row;}">

            {{-- ── LEFT ── --}}
            <div style="flex:2; min-width:0; display:flex; flex-direction:column; gap:1.75rem;">

                {{-- Key Features --}}
                <div class="detail-card">
                    <div class="card-section-label">Overview</div>
                    <h2 class="card-heading">Key Features</h2>
                    <div class="feat-grid">
                        @if($property->bedrooms)
                            <div class="feat-item">
                                <span class="feat-icon">🛏</span>
                                <span class="feat-label">Bedrooms</span>
                                <span class="feat-value">{{ $property->bedrooms }}</span>
                            </div>
                        @endif
                        @if($property->bathrooms)
                            <div class="feat-item">
                                <span class="feat-icon">🚿</span>
                                <span class="feat-label">Bathrooms</span>
                                <span class="feat-value">{{ $property->bathrooms }}</span>
                            </div>
                        @endif
                        @if($property->area)
                            <div class="feat-item">
                                <span class="feat-icon">📐</span>
                                <span class="feat-label">Area</span>
                                <span class="feat-value" style="font-size:0.9rem;">{{ number_format($property->area) }} <span style="font-size:0.7rem; color:#8c8070; font-weight:400;">sqft</span></span>
                            </div>
                        @endif
                        @if($property->parking)
                            <div class="feat-item">
                                <span class="feat-icon">🚗</span>
                                <span class="feat-label">Parking</span>
                                <span class="feat-value available">Available</span>
                            </div>
                        @endif
                        @if($property->water)
                            <div class="feat-item">
                                <span class="feat-icon">💧</span>
                                <span class="feat-label">Water</span>
                                <span class="feat-value available">Available</span>
                            </div>
                        @endif
                        @if($property->furnished)
                            <div class="feat-item">
                                <span class="feat-icon">🪑</span>
                                <span class="feat-label">Furnished</span>
                                <span class="feat-value">{{ ucfirst($property->furnished) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($property->description)
                    <div class="detail-card">
                        <div class="card-section-label">About</div>
                        <h2 class="card-heading">Property Description</h2>
                        <p class="prop-description">{{ $property->description }}</p>
                    </div>
                @endif

                {{-- Amenities --}}
                @if($property->features && count($property->features) > 0)
                    <div class="detail-card">
                        <div class="card-section-label">Amenities</div>
                        <h2 class="card-heading">Features & Amenities</h2>
                        <div style="display:flex; flex-wrap:wrap; gap:0.625rem;">
                            @foreach($property->features as $feature)
                                <span class="amenity-tag">
                                    <span class="amenity-dot"></span>
                                    {{ $feature }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Property Details table --}}
                <div class="detail-card">
                    <div class="card-section-label">Specifics</div>
                    <h2 class="card-heading">Property Details</h2>
                    <div class="detail-table">
                        <div class="detail-row">
                            <span class="detail-row-label">Property ID</span>
                            <span class="detail-row-value">#{{ str_pad($property->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row-label">Type</span>
                            <span class="detail-row-value">{{ ucfirst($property->type) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-row-label">Purpose</span>
                            <span class="detail-row-value">For {{ ucfirst($property->purpose) }}</span>
                        </div>
                        @isset($property->category)
                            <div class="detail-row">
                                <span class="detail-row-label">Category</span>
                                <span class="detail-row-value">{{ ucfirst($property->category) }}</span>
                            </div>
                        @endisset
                        @if($property->area)
                            <div class="detail-row">
                                <span class="detail-row-label">Area</span>
                                <span class="detail-row-value">{{ number_format($property->area) }} sqft</span>
                            </div>
                        @endif
                        @if($property->furnished)
                            <div class="detail-row">
                                <span class="detail-row-label">Furnished</span>
                                <span class="detail-row-value">{{ ucfirst($property->furnished) }}</span>
                            </div>
                        @endif
                        <div class="detail-row">
                            <span class="detail-row-label">Location</span>
                            <span class="detail-row-value">{{ $property->location }}</span>
                        </div>
                    </div>
                </div>

                {{-- Map --}}
                @if($property->latitude && $property->longitude)
                    <div class="detail-card">
                        <div class="card-section-label">Location</div>
                        <h2 class="card-heading">Map View</h2>
                        <div class="map-placeholder">
                            <div style="text-align:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.5" style="display:block; margin:0 auto 0.75rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.788L9 2m0 18l6-3m-6 3V7m6 13l4.553-2.276A2 2 0 0021 16.382V5.618a2 2 0 00-1.105-1.788L15 2m0 18V7"/>
                                </svg>
                                {{ $property->location }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── RIGHT SIDEBAR ── --}}
            <div style="flex:0 0 22rem; min-width:0;">
                <div class="sidebar-sticky">

                    {{-- Price card --}}
                    <div class="price-card">
                        <div class="price-label">Asking Price</div>
                        <div class="price-value">Rs {{ number_format($property->price) }}</div>
                        @if($property->purpose === 'rent')
                            <div class="price-per">per month</div>
                        @endif

                        <div class="price-divider"></div>

                        <div class="contact-heading">Enquire About This Property</div>

                        <form>
                            <input type="text"   class="contact-input" placeholder="Your Name">
                            <input type="email"  class="contact-input" placeholder="Email Address">
                            <input type="tel"    class="contact-input" placeholder="Phone Number">
                            <button type="submit" class="contact-btn">Request Information</button>
                        </form>

                        <div class="action-row">
                            <button class="action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                Save
                            </button>
                            <button class="action-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                Share
                            </button>
                        </div>
                    </div>

                    {{-- Property ID --}}
                    <div class="prop-id-tag">
                        Property ID &nbsp;<span>#{{ str_pad($property->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

</x-public>