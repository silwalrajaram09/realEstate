<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .show-root { font-family: 'DM Sans', sans-serif; background: var(--mist, #f4f0e8); min-height: 100%; }

        /* ── Page header ── */
        .show-page-header { background: var(--cream, #faf7f2); border-bottom: 1px solid #ede8df; padding: 1.5rem 0; }
        .show-eyebrow { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.35rem; }
        .show-eyebrow-line { width: 1.5rem; height: 1px; background: #b5813a; }
        .show-eyebrow-text { font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: #b5813a; font-weight: 600; }
        .show-page-title { font-family: 'Playfair Display', serif; font-size: clamp(1.25rem, 2.5vw, 1.85rem); font-weight: 600; color: #1a1a2e; line-height: 1.1; }

        /* ── Back link ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.8rem; font-weight: 500; color: #8c8070; text-decoration: none;
            letter-spacing: 0.04em; text-transform: uppercase;
            transition: color 0.15s;
        }
        .back-link:hover { color: #b5813a; }

        /* ── Edit button ── */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            background: #b5813a; color: #fff;
            border-radius: 0.5rem; font-size: 0.8125rem; font-weight: 600;
            letter-spacing: 0.04em; text-transform: uppercase; text-decoration: none;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gold:hover { background: #9a6e2f; box-shadow: 0 4px 14px rgba(181,129,58,0.3); }

        /* ── Card ── */
        .card { background: var(--cream, #faf7f2); border: 1px solid #ede8df; border-radius: 0.75rem; overflow: hidden; }
        .card-hero { height: 18rem; background: #ede8df; }
        .card-hero img { width: 100%; height: 100%; object-fit: cover; }
        .card-hero-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #8c8070; }
        .card-body { padding: 2rem; }

        /* ── Property headline ── */
        .prop-title { font-family: 'Playfair Display', serif; font-size: clamp(1.35rem, 2.5vw, 1.85rem); font-weight: 600; color: #1a1a2e; }
        .prop-loc { display: flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: #8c8070; margin-top: 0.5rem; }
        .prop-price { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 600; color: #b5813a; }

        /* ── Badges ── */
        .badge { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.05em; }
        .badge-sale     { background: #ecfdf5; color: #059669; }
        .badge-rent     { background: #eff6ff; color: #2563eb; }
        .badge-approved { background: #ecfdf5; color: #059669; }
        .badge-pending  { background: #fffbeb; color: #d97706; }
        .badge-rejected { background: #fef2f2; color: #dc2626; }
        .badge-neutral  { background: #f4f0e8; color: #8c8070; border: 1px solid #ede8df; }

        /* ── Section heading ── */
        .section-label {
            font-size: 0.65rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: #b5813a;
            display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;
        }
        .section-label::after { content: ''; flex: 1; height: 1px; background: #ede8df; }

        /* ── Stat tiles ── */
        .stat-tile { background: #f4f0e8; border: 1px solid #ede8df; border-radius: 0.625rem; padding: 1.1rem; text-align: center; }
        .stat-tile-value { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 600; color: #1a1a2e; }
        .stat-tile-label { font-size: 0.72rem; color: #8c8070; margin-top: 0.2rem; text-transform: uppercase; letter-spacing: 0.06em; }

        /* ── Amenity rows ── */
        .amenity-row { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; }
        .amenity-yes { color: #059669; }
        .amenity-no  { color: #8c8070; }

        /* ── Divider ── */
        .re-divider { border: none; border-top: 1px solid #ede8df; margin: 1.75rem 0; }

        /* ── Timestamps ── */
        .ts-text { font-size: 0.78rem; color: #8c8070; }
    </style>

    <div class="show-root">

        {{-- Page header --}}
        <div class="show-page-header">
            <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="show-eyebrow">
                            <span class="show-eyebrow-line"></span>
                            <span class="show-eyebrow-text">Property Detail</span>
                        </div>
                        <h1 class="show-page-title">{{ $property->title }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('seller.properties.index') }}" class="back-link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a>
                        <a href="{{ route('seller.properties.edit', $property->id) }}" class="btn-gold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-5 sm:px-8 lg:px-10 py-8">
            <div class="card reveal">

                {{-- Hero image --}}
                <div class="card-hero">
                    @if($property->image)
                        <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}">
                    @else
                        <div class="card-hero-empty">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="card-body">

                    {{-- Headline row --}}
                    <div style="display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:space-between;align-items:flex-start;margin-bottom:1.25rem">
                        <div>
                            <h2 class="prop-title">{{ $property->title }}</h2>
                            <div class="prop-loc">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ $property->location }}
                            </div>
                        </div>
                        <div style="text-align:right">
                            <p class="prop-price">Rs {{ number_format($property->price) }}</p>
                            <span class="badge {{ $property->purpose === 'buy' ? 'badge-sale' : 'badge-rent' }}" style="margin-top:0.4rem">
                                For {{ ucfirst($property->purpose) }}
                            </span>
                        </div>
                    </div>

                    {{-- Meta badges --}}
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.75rem">
                        <span class="badge badge-neutral">{{ ucfirst($property->type) }}</span>
                        <span class="badge badge-neutral">{{ ucfirst($property->category) }}</span>
                        <span class="badge
                            @if($property->status === 'approved') badge-approved
                            @elseif($property->status === 'pending') badge-pending
                            @else badge-rejected @endif">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>

                    {{-- Description --}}
                    @if($property->description)
                        <div style="margin-bottom:1.75rem">
                            <p class="section-label">Description</p>
                            <p style="font-size:0.9rem;color:#4a4a5a;line-height:1.7">{{ $property->description }}</p>
                        </div>
                    @endif

                    {{-- Feature tiles --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3" style="margin-bottom:1.75rem">
                        @if($property->bedrooms)
                            <div class="stat-tile">
                                <p class="stat-tile-value">{{ $property->bedrooms }}</p>
                                <p class="stat-tile-label">Bedrooms</p>
                            </div>
                        @endif
                        @if($property->bathrooms)
                            <div class="stat-tile">
                                <p class="stat-tile-value">{{ $property->bathrooms }}</p>
                                <p class="stat-tile-label">Bathrooms</p>
                            </div>
                        @endif
                        <div class="stat-tile">
                            <p class="stat-tile-value">{{ $property->area }}</p>
                            <p class="stat-tile-label">Sq.ft</p>
                        </div>
                        <div class="stat-tile">
                            <p class="stat-tile-value">{{ $property->area * 9 }}</p>
                            <p class="stat-tile-label">Sq.m</p>
                        </div>
                    </div>

                    <hr class="re-divider">

                    {{-- Amenities --}}
                    <p class="section-label">Amenities</p>
                    <div class="grid grid-cols-2 gap-3" style="margin-bottom:1.5rem">
                        <div class="amenity-row {{ $property->parking ? 'amenity-yes' : 'amenity-no' }}">
                            @if($property->parking)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Parking Available</span>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>No Parking</span>
                            @endif
                        </div>
                        <div class="amenity-row {{ $property->water ? 'amenity-yes' : 'amenity-no' }}">
                            @if($property->water)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Water Supply</span>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>No Water Supply</span>
                            @endif
                        </div>
                    </div>

                    <hr class="re-divider">

                    {{-- Timestamps --}}
                    <div style="display:flex;gap:1.5rem;flex-wrap:wrap">
                        <p class="ts-text">Listed: <strong style="color:#1a1a2e">{{ $property->created_at->format('M d, Y') }}</strong></p>
                        <p class="ts-text">Updated: <strong style="color:#1a1a2e">{{ $property->updated_at->format('M d, Y') }}</strong></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>