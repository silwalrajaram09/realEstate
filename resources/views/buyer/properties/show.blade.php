<x-app-layout>
@php
    $isBuy = $property->purpose === 'buy';
@endphp

<style>
    .show-root { font-family: 'Outfit', sans-serif; -webkit-font-smoothing: antialiased; }

    /* ── Breadcrumb ── */
    .breadcrumb-bar {
        background: #fff;
        border-bottom: 1px solid #ede8df;
        position: sticky;
        top: 4rem;
        z-index: 30;
        padding: 0.75rem 0;
    }
    .breadcrumb-inner {
        max-width: 1280px; margin: 0 auto; padding: 0 2rem;
        display: flex; align-items: center; gap: 0.375rem;
        font-size: 0.75rem; color: #8c8070;
    }
    .breadcrumb-inner a { color: #8c8070; text-decoration: none; transition: color 0.2s ease; display: flex; align-items: center; gap: 0.25rem; }
    .breadcrumb-inner a:hover { color: #c9a96e; }
    .breadcrumb-inner .sep { color: #ddd5c8; font-size: 0.6rem; }
    .breadcrumb-inner .current { color: #0f0f0f; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 20rem; }

    /* ── Main image ── */
    .img-wrap { position: relative; height: 520px; background: #1a1510; overflow: hidden; cursor: pointer; }
    .img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s ease; }
    .img-wrap:hover img { transform: scale(1.04); }
    .img-wrap-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(10,8,5,0.55) 0%, transparent 50%); pointer-events: none; }
    .zoom-hint {
        position: absolute; bottom: 1rem; right: 1rem;
        display: flex; align-items: center; gap: 0.375rem;
        padding: 0.35rem 0.875rem; background: rgba(15,15,15,0.6); backdrop-filter: blur(6px);
        border-radius: 2px; color: rgba(255,255,255,0.8); font-size: 0.7rem; letter-spacing: 0.06em;
        opacity: 0; transition: opacity 0.25s ease; pointer-events: none;
    }
    .img-wrap:hover .zoom-hint { opacity: 1; }

    .img-badge { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; padding: 0.3rem 0.75rem; border-radius: 2px; display: inline-flex; align-items: center; gap: 0.35rem; }
    .img-badge-buy { background: #c9a96e; color: #0f0f0f; }
    .img-badge-rent { background: #0f0f0f; color: #c9a96e; border: 1px solid #c9a96e; }
    .img-badge-verified { background: rgba(255,255,255,0.92); color: #3a7a3a; backdrop-filter: blur(4px); }
    .img-counter { position: absolute; top: 1rem; right: 1rem; background: rgba(15,15,15,0.55); backdrop-filter: blur(4px); color: rgba(255,255,255,0.8); font-size: 0.7rem; font-weight: 500; letter-spacing: 0.06em; padding: 0.3rem 0.75rem; border-radius: 2px; }

    /* Gallery strip */
    .gallery-strip { display: flex; gap: 0.5rem; padding: 0.875rem 1.25rem; border-top: 1px solid #f0ece4; background: #faf7f2; overflow-x: auto; scrollbar-width: none; }
    .gallery-strip::-webkit-scrollbar { display: none; }
    .gallery-thumb { flex-shrink: 0; width: 5rem; height: 3.75rem; border-radius: 3px; overflow: hidden; border: 1.5px solid transparent; cursor: pointer; opacity: 0.65; transition: opacity 0.2s ease, border-color 0.2s ease; }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumb.active, .gallery-thumb:hover { opacity: 1; border-color: #c9a96e; }

    /* Cards */
    .detail-card { background: #fff; border: 1px solid #ede8df; border-radius: 4px; padding: 1.75rem 2rem; margin-bottom: 1.5rem; }
    .section-eyebrow { display: flex; align-items: center; gap: 0.5rem; font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: #c9a96e; font-weight: 600; margin-bottom: 0.5rem; }
    .section-eyebrow::before { content: ''; display: block; width: 1rem; height: 1px; background: #c9a96e; }
    .section-heading { font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 600; color: #0f0f0f; margin-bottom: 1.25rem; }

    .prop-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(1.75rem, 3.5vw, 2.5rem); font-weight: 600; color: #0f0f0f; line-height: 1.1; margin-bottom: 0.5rem; }
    .prop-location { display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem; font-weight: 300; color: #8c8070; }

    .type-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.875rem; border-radius: 2px; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; background: rgba(201,169,110,0.1); border: 1px solid rgba(201,169,110,0.25); color: #9a7340; }

    .share-btn { width: 2.25rem; height: 2.25rem; border-radius: 3px; border: 1px solid #ddd5c8; background: #faf7f2; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: border-color 0.2s ease, background 0.2s ease; }
    .share-btn:hover { border-color: #c9a96e; background: rgba(201,169,110,0.08); }

    /* Feature grid */
    .feat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    @media (min-width: 640px) { .feat-grid { grid-template-columns: repeat(4, 1fr); } }
    .feat-item { text-align: center; padding: 1rem 0.5rem; border: 1px solid #f0ece4; border-radius: 3px; background: #faf7f2; transition: border-color 0.2s ease; }
    .feat-item:hover { border-color: #c9a96e; }
    .feat-emoji { font-size: 1.5rem; display: block; margin-bottom: 0.35rem; }
    .feat-label { font-size: 0.58rem; letter-spacing: 0.1em; text-transform: uppercase; color: #b0a090; font-weight: 500; display: block; }
    .feat-value { font-size: 1rem; font-weight: 600; color: #0f0f0f; display: block; margin-top: 0.2rem; }
    .feat-value.avail { color: #5a8a5a; }

    .prop-desc { font-size: 0.9375rem; font-weight: 300; color: #4a4038; line-height: 1.85; }
    .read-more-btn { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #c9a96e; background: none; border: none; cursor: pointer; margin-top: 0.75rem; display: inline-flex; align-items: center; gap: 0.375rem; padding: 0; }

    .amenity-row { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 0.875rem; border-radius: 3px; border: 1px solid #f0ece4; font-size: 0.8125rem; }
    .amenity-row.avail { background: rgba(90,138,90,0.05); border-color: rgba(90,138,90,0.2); }
    .amenity-row.unavail { background: #faf7f2; }
    .amenity-name { display: flex; align-items: center; gap: 0.5rem; font-weight: 400; color: #3a3028; }
    .amenity-name.dim { color: #b0a090; }

    .detail-row { display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 0; border-bottom: 1px solid #f4ede3; font-size: 0.8375rem; }
    .detail-row:last-child { border-bottom: none; }
    .detail-key { color: #8c8070; font-weight: 300; }
    .detail-val { font-weight: 500; color: #0f0f0f; }

    .map-box { border-radius: 3px; overflow: hidden; border: 1px solid #ede8df; }
    .map-controls { background: #faf7f2; border-top: 1px solid #f0ece4; padding: 0.875rem 1rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
    .map-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 1rem; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; border-radius: 3px; cursor: pointer; transition: all 0.2s ease; }
    .map-btn-ghost { background: #fff; border: 1px solid #ddd5c8; color: #3a3028; }
    .map-btn-ghost:hover { border-color: #c9a96e; color: #9a7340; }
    .map-btn-gold { background: #c9a96e; border: 1px solid #c9a96e; color: #0f0f0f; }
    .map-btn-gold:hover { background: #b5924f; border-color: #b5924f; }
    .map-type-toggle { display: flex; align-items: center; gap: 0.25rem; background: #fff; border: 1px solid #ddd5c8; border-radius: 3px; padding: 0.2rem; }
    .map-type-btn { padding: 0.3rem 0.75rem; font-size: 0.7rem; font-weight: 500; border-radius: 2px; border: none; cursor: pointer; color: #4a4038; background: none; transition: background 0.2s ease; }
    .map-type-btn.active { background: #c9a96e; color: #0f0f0f; font-weight: 600; }

    /* Sidebar */
    .sidebar-sticky { position: sticky; top: 8rem; display: flex; flex-direction: column; gap: 1rem; }

    .price-card { background: #0f0f0f; border-radius: 4px; padding: 1.75rem; position: relative; overflow: hidden; }
    .price-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(to right, #c9a96e, transparent); }
    .price-card-label { font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(255,255,255,0.4); font-weight: 500; }
    .price-card-value { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 600; color: #c9a96e; line-height: 1; margin: 0.375rem 0 0.2rem; }
    .price-card-per { font-size: 0.72rem; color: rgba(255,255,255,0.35); font-weight: 300; }
    .price-card-divider { height: 1px; background: rgba(255,255,255,0.07); margin: 1.375rem 0; }
    .price-card-heading { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 600; color: #fff; margin-bottom: 1rem; }

    .contact-input { width: 100%; display: block; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 3px; padding: 0.7rem 0.875rem; font-family: 'Outfit', sans-serif; font-size: 0.8125rem; font-weight: 300; color: #fff; margin-bottom: 0.625rem; transition: border-color 0.2s ease, background 0.2s ease; box-sizing: border-box; }
    .contact-input::placeholder { color: rgba(255,255,255,0.25); }
    .contact-input:focus { outline: none; border-color: #c9a96e; background: rgba(255,255,255,0.09); }
    .contact-submit { width: 100%; padding: 0.8rem; background: #c9a96e; color: #0f0f0f; font-family: 'Outfit', sans-serif; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; border: none; border-radius: 3px; cursor: pointer; transition: background 0.2s ease; }
    .contact-submit:hover { background: #b5924f; }

    .action-row { display: flex; gap: 0.625rem; margin-top: 0.875rem; }
    .action-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.6rem; font-size: 0.68rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(255,255,255,0.55); border: 1px solid rgba(255,255,255,0.1); border-radius: 3px; background: none; cursor: pointer; transition: all 0.2s ease; }
    .action-btn:hover { border-color: rgba(201,169,110,0.4); color: #c9a96e; background: rgba(201,169,110,0.06); }

    .emi-card { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.07); }
    .emi-label { font-size: 0.62rem; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255,255,255,0.35); font-weight: 500; margin-bottom: 0.75rem; display: block; }
    .emi-row { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 0.375rem; }
    .emi-key { color: rgba(255,255,255,0.4); font-weight: 300; }
    .emi-val { color: rgba(255,255,255,0.75); font-weight: 500; }
    .emi-val.highlight { color: #c9a96e; font-weight: 600; font-size: 0.9rem; }
    .emi-note { font-size: 0.62rem; color: rgba(255,255,255,0.2); margin-top: 0.5rem; font-weight: 300; }

    .prop-id-tag { text-align: center; padding: 0.75rem; border: 1px solid #ede8df; border-radius: 3px; background: #faf7f2; font-size: 0.72rem; color: #8c8070; letter-spacing: 0.05em; }
    .prop-id-tag span { font-weight: 600; color: #4a4038; font-family: 'Courier New', monospace; }

    .seller-card { background: #fff; border: 1px solid #ede8df; border-radius: 4px; padding: 1.5rem; }
    .seller-avatar { width: 3.5rem; height: 3.5rem; border-radius: 50%; background: linear-gradient(135deg, #c9a96e, #9a7340); display: flex; align-items: center; justify-content: center; color: #fff; font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 600; flex-shrink: 0; }
    .seller-name { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 600; color: #0f0f0f; }
    .seller-badge { display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0.2rem 0.6rem; border-radius: 2px; background: rgba(90,138,90,0.1); border: 1px solid rgba(90,138,90,0.25); color: #3a7a3a; }
    .seller-stat-num { font-family: 'Cormorant Garamond', serif; font-size: 1.35rem; font-weight: 600; color: #0f0f0f; display: block; }
    .seller-stat-lbl { font-size: 0.62rem; letter-spacing: 0.08em; text-transform: uppercase; color: #8c8070; font-weight: 500; }
    .seller-call-btn { width: 100%; padding: 0.75rem; background: #0f0f0f; color: #fff; font-family: 'Outfit', sans-serif; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; border: none; border-radius: 3px; cursor: pointer; margin-top: 0.75rem; transition: background 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .seller-call-btn:hover { background: #1a1a1a; }
    .seller-msg-btn { width: 100%; padding: 0.75rem; background: transparent; color: #4a4038; font-family: 'Outfit', sans-serif; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; border: 1px solid #ddd5c8; border-radius: 3px; cursor: pointer; margin-top: 0.5rem; transition: border-color 0.2s ease, background 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .seller-msg-btn:hover { border-color: #c9a96e; background: rgba(201,169,110,0.05); }

    .safety-card { background: #faf7f2; border: 1px solid #ede8df; border-radius: 4px; padding: 1.375rem 1.5rem; }
    .safety-item { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.8125rem; color: #4a4038; font-weight: 300; line-height: 1.6; }
    .safety-num { width: 1.375rem; height: 1.375rem; flex-shrink: 0; border-radius: 50%; background: rgba(201,169,110,0.15); border: 1px solid rgba(201,169,110,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700; color: #9a7340; margin-top: 0.1rem; }

    .rv-card { background: #fff; border: 1px solid #ede8df; border-radius: 4px; padding: 1.375rem 1.5rem; }
    .rv-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0.375rem; border-radius: 3px; text-decoration: none; transition: background 0.15s ease; }
    .rv-item:hover { background: #faf7f2; }
    .rv-thumb { width: 2.875rem; height: 2.25rem; border-radius: 2px; overflow: hidden; flex-shrink: 0; background: #e8e0d4; }
    .rv-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rv-title { font-size: 0.8rem; font-weight: 500; color: #0f0f0f; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .rv-loc { font-size: 0.7rem; color: #8c8070; font-weight: 300; margin-top: 0.1rem; }
    .rv-price { font-size: 0.72rem; font-weight: 600; color: #c9a96e; margin-top: 0.15rem; }

    .rec-card { background: #fff; border: 1px solid #ede8df; border-radius: 4px; overflow: hidden; text-decoration: none; display: block; transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease; }
    .rec-card:hover { box-shadow: 0 12px 36px rgba(0,0,0,0.08); transform: translateY(-3px); border-color: transparent; }
    .rec-img { position: relative; aspect-ratio: 4/3; overflow: hidden; }
    .rec-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .rec-card:hover .rec-img img { transform: scale(1.05); }
    .rec-body { padding: 1.125rem 1.25rem; }
    .rec-title { font-family: 'Cormorant Garamond', serif; font-size: 1.05rem; font-weight: 600; color: #0f0f0f; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; transition: color 0.2s ease; }
    .rec-card:hover .rec-title { color: #c9a96e; }
    .rec-loc { font-size: 0.75rem; font-weight: 300; color: #8c8070; margin-top: 0.2rem; display: flex; align-items: center; gap: 0.25rem; }
    .rec-price { font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 600; color: #c9a96e; margin-top: 0.5rem; }

    .gold-rule { height: 1px; background: linear-gradient(to right, #c9a96e, transparent); border: none; margin: 0; }

    /* Lightbox */
    .lightbox { position: fixed; inset: 0; background: rgba(10,8,5,0.93); z-index: 200; display: none; align-items: center; justify-content: center; }
    .lightbox.open { display: flex; }
    .lightbox img { max-height: 90vh; max-width: 90vw; object-fit: contain; border-radius: 2px; }
    .lb-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 2.75rem; height: 2.75rem; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.06); backdrop-filter: blur(6px); color: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem; transition: background 0.2s ease, border-color 0.2s ease; }
    .lb-btn:hover { background: rgba(201,169,110,0.2); border-color: rgba(201,169,110,0.5); }
    .lb-close { position: absolute; top: 1.5rem; right: 1.5rem; width: 2.25rem; height: 2.25rem; border-radius: 50%; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.2s ease; }
    .lb-close:hover { background: rgba(192,57,43,0.3); }
</style>

<div class="show-root" style="background:#f4f0e8;">

    {{-- ── BREADCRUMB ── --}}
    <div class="breadcrumb-bar">
        <div class="breadcrumb-inner">
            <a href="{{ route('buyer.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <span class="sep">›</span>
            <a href="{{ route('buyer.properties') }}">Properties</a>
            <span class="sep">›</span>
            <span class="current">{{ $property->title }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- ── LEFT COLUMN ── --}}
            <div style="flex:2; min-width:0;">

                {{-- Image card --}}
                <div class="detail-card" style="padding:0; overflow:hidden; margin-bottom:1.5rem;">
                    <div class="img-wrap" onclick="openLightbox()">
                        @if($property->image_url)
                            <img src="{{ $property->image_url }}" alt="{{ $property->title }}" id="mainPropertyImage" loading="eager">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#1a1510;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="rgba(201,169,110,0.4)" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="img-wrap-overlay"></div>
                        <div style="position:absolute;top:1rem;left:1rem;display:flex;gap:0.375rem;z-index:2;">
                            <span class="img-badge {{ $isBuy ? 'img-badge-buy' : 'img-badge-rent' }}">For {{ $isBuy ? 'Sale' : 'Rent' }}</span>
                            @if($property->status === 'approved')
                                <span class="img-badge img-badge-verified">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        @if($property->gallery ?? false)
                            <div class="img-counter"><span id="currentImageIndex">1</span>/{{ count($property->gallery) + 1 }}</div>
                        @endif
                        <div class="zoom-hint">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            Click to zoom
                        </div>
                    </div>

                    @if($property->gallery ?? false)
                        <div class="gallery-strip">
                            <div class="gallery-thumb active" onclick="changeImage(0)"><img src="{{ $property->image_url }}" alt="Main"></div>
                            @foreach($property->gallery as $i => $img)
                                <div class="gallery-thumb" onclick="changeImage({{ $i + 1 }})"><img src="{{ $img }}" alt="Gallery {{ $i+1 }}"></div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Title + share --}}
                    <div style="padding:1.75rem 2rem; border-top:1px solid #f0ece4;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                            <div style="flex:1;min-width:0;">
                                <h1 class="prop-title">{{ $property->title }}</h1>
                                <div class="prop-location">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $property->location }}
                                </div>
                            </div>
                            <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                                <button class="share-btn" onclick="shareOnFacebook()" title="Facebook"><svg viewBox="0 0 24 24" width="14" height="14" fill="#4267B2"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg></button>
                                <button class="share-btn" onclick="shareOnTwitter()" title="Twitter"><svg viewBox="0 0 24 24" width="14" height="14" fill="#1DA1F2"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg></button>
                                <button class="share-btn" onclick="copyToClipboard()" title="Copy link"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#4a4038" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></button>
                            </div>
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:1rem;">
                            <span class="type-badge">{{ ucfirst($property->type) }}</span>
                            @isset($property->category)<span class="type-badge">{{ ucfirst($property->category) }}</span>@endisset
                            <span class="type-badge">Listed {{ $property->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Key Features --}}
                <div class="detail-card">
                    <div class="section-eyebrow">Overview</div>
                    <div class="section-heading">Key Features</div>
                    <div class="feat-grid">
                        @if($property->bedrooms)
                            <div class="feat-item"><span class="feat-emoji">🛏</span><span class="feat-label">Bedrooms</span><span class="feat-value">{{ $property->bedrooms }}</span></div>
                        @endif
                        @if($property->bathrooms)
                            <div class="feat-item"><span class="feat-emoji">🚿</span><span class="feat-label">Bathrooms</span><span class="feat-value">{{ $property->bathrooms }}</span></div>
                        @endif
                        @if($property->area)
                            <div class="feat-item"><span class="feat-emoji">📐</span><span class="feat-label">Area</span><span class="feat-value" style="font-size:0.875rem;">{{ number_format($property->area) }}<span style="font-size:0.65rem;color:#8c8070;font-weight:400;"> sqft</span></span></div>
                        @endif
                        <div class="feat-item"><span class="feat-emoji">📅</span><span class="feat-label">Listed</span><span class="feat-value" style="font-size:0.85rem;">{{ $property->created_at->format('M Y') }}</span></div>
                        @if($property->parking)<div class="feat-item"><span class="feat-emoji">🚗</span><span class="feat-label">Parking</span><span class="feat-value avail">Yes</span></div>@endif
                        @if($property->furnished)<div class="feat-item"><span class="feat-emoji">🪑</span><span class="feat-label">Furnished</span><span class="feat-value">{{ ucfirst($property->furnished) }}</span></div>@endif
                    </div>
                </div>

                {{-- Description --}}
                @if($property->description)
                    <div class="detail-card">
                        <div class="section-eyebrow">About</div>
                        <div class="section-heading">Property Description</div>
                        <p class="prop-desc">{{ Str::limit($property->description, 320) }}</p>
                        @if(strlen($property->description) > 320)
                            <span id="descMore" class="hidden prop-desc">{{ substr($property->description, 320) }}</span>
                            <button class="read-more-btn" id="descBtn" onclick="toggleDesc()">Read More ↓</button>
                        @endif
                    </div>
                @endif

                {{-- Amenities --}}
                <div class="detail-card">
                    <div class="section-eyebrow">Amenities</div>
                    <div class="section-heading">Features & Amenities</div>
                    @php
                        $amenities = [
                            'Parking'       => ['avail' => $property->parking   ?? false, 'icon' => '🚗'],
                            'Water Supply'  => ['avail' => $property->water     ?? false, 'icon' => '💧'],
                            'Electricity'   => ['avail' => true,                           'icon' => '⚡'],
                            'Security'      => ['avail' => $property->security  ?? false, 'icon' => '🔒'],
                            'Internet'      => ['avail' => $property->internet  ?? false, 'icon' => '🌐'],
                            'Furnished'     => ['avail' => (bool)($property->furnished ?? false), 'icon' => '🪑'],
                            'Gym'           => ['avail' => $property->gym       ?? false, 'icon' => '💪'],
                            'Swimming Pool' => ['avail' => $property->pool      ?? false, 'icon' => '🏊'],
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($amenities as $label => $data)
                            <div class="amenity-row {{ $data['avail'] ? 'avail' : 'unavail' }}">
                                <div class="amenity-name {{ $data['avail'] ? '' : 'dim' }}">
                                    <span style="font-size:1rem;">{{ $data['icon'] }}</span>
                                    {{ $label }}
                                </div>
                                @if($data['avail'])
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#5a8a5a" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c8b9a8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Property Details --}}
                <div class="detail-card">
                    <div class="section-eyebrow">Specifics</div>
                    <div class="section-heading">Property Details</div>
                    <div class="detail-row"><span class="detail-key">Property ID</span><span class="detail-val">#{{ str_pad($property->id, 8, '0', STR_PAD_LEFT) }}</span></div>
                    <div class="detail-row"><span class="detail-key">Type</span><span class="detail-val">{{ ucfirst($property->type) }}</span></div>
                    <div class="detail-row"><span class="detail-key">Purpose</span><span class="detail-val">For {{ $isBuy ? 'Sale' : 'Rent' }}</span></div>
                    @isset($property->category)<div class="detail-row"><span class="detail-key">Category</span><span class="detail-val">{{ ucfirst($property->category) }}</span></div>@endisset
                    @if($property->area)<div class="detail-row"><span class="detail-key">Area</span><span class="detail-val">{{ number_format($property->area) }} sqft</span></div>@endif
                    @if($property->furnished)<div class="detail-row"><span class="detail-key">Furnished</span><span class="detail-val">{{ ucfirst($property->furnished) }}</span></div>@endif
                    <div class="detail-row"><span class="detail-key">Location</span><span class="detail-val">{{ $property->location }}</span></div>
                    <div class="detail-row"><span class="detail-key">Status</span><span class="detail-val" style="color:#5a8a5a;">{{ ucfirst($property->status ?? 'Active') }}</span></div>
                    <div class="detail-row"><span class="detail-key">Listed</span><span class="detail-val">{{ $property->created_at->format('d M Y') }}</span></div>
                </div>

                {{-- Map --}}
                <div class="detail-card">
                    <div class="section-eyebrow">Location</div>
                    <div class="section-heading">Map View</div>
                    <div class="map-box">
                        <div id="propertyMap" style="height:380px;width:100%;background:#e8e0d4;position:relative;z-index:0;"></div>
                        <div class="map-controls">
                            <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                <button class="map-btn map-btn-ghost" onclick="zoomToLocation()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    Zoom In
                                </button>
                                <button class="map-btn map-btn-gold" onclick="getDirections()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.788L9 2m0 18l6-3m-6 3V7m6 13l4.553-2.276A2 2 0 0021 16.382V5.618a2 2 0 00-1.105-1.788L15 2m0 18V7"/></svg>
                                    Get Directions
                                </button>
                            </div>
                            <div class="map-type-toggle">
                                <button class="map-type-btn active" onclick="setMapType('street',this)">Street</button>
                                <button class="map-type-btn" onclick="setMapType('satellite',this)">Satellite</button>
                                <button class="map-type-btn" onclick="setMapType('terrain',this)">Terrain</button>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:0.72rem;color:#b0a090;font-weight:300;margin-top:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Lat: {{ $property->latitude ?? '—' }} · Lng: {{ $property->longitude ?? '—' }}
                        &nbsp;·&nbsp;
                        <a href="https://www.openstreetmap.org/directions?from=&to={{ $property->latitude ?? 0 }},{{ $property->longitude ?? 0 }}" target="_blank" style="color:#c9a96e;text-decoration:none;">Open in OSM →</a>
                    </p>
                </div>

            </div>

            {{-- ── RIGHT SIDEBAR ── --}}
            <div style="flex:0 0 22rem;min-width:0;width:100%;">
                <div class="sidebar-sticky">

                    {{-- Price card --}}
                    <div class="price-card">
                        <div class="price-card-label">Asking Price</div>
                        <div class="price-card-value">Rs {{ number_format($property->price) }}</div>
                        @if(!$isBuy)<div class="price-card-per">per month</div>@endif
                        <div class="price-card-divider"></div>
                        <div class="price-card-heading">Enquire About This Property</div>
                        <form>
                            <input type="text"  class="contact-input" placeholder="Your Name">
                            <input type="email" class="contact-input" placeholder="Email Address">
                            <input type="tel"   class="contact-input" placeholder="Phone Number">
                            <button type="submit" class="contact-submit">Request Information</button>
                        </form>
                        <div class="action-row">
                            <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>Save</button>
                            <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>Share</button>
                            <button class="action-btn"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Visit</button>
                        </div>
                        @if($isBuy)
                            <div class="emi-card">
                                <span class="emi-label">EMI Estimate · 10% · 20 yrs</span>
                                <div class="emi-row"><span class="emi-key">Loan (80%)</span><span class="emi-val">Rs {{ number_format($property->price * 0.8) }}</span></div>
                                <div class="emi-row"><span class="emi-key">Monthly EMI</span><span class="emi-val highlight">Rs {{ number_format(($property->price * 0.8 * 0.00833) / (1 - pow(1 + 0.00833, -240))) }}</span></div>
                                <p class="emi-note">*Indicative only. Consult your bank for exact figures.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Property ID --}}
                    <div class="prop-id-tag">Property ID &nbsp;<span>#{{ str_pad($property->id, 8, '0', STR_PAD_LEFT) }}</span></div>

                    {{-- Seller card --}}
                    <div class="seller-card">
                        <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:1rem;">
                            <div class="seller-avatar">{{ strtoupper(substr($property->seller->name ?? 'NA', 0, 2)) }}</div>
                            <div>
                                <div class="seller-name">{{ $property->seller->name ?? 'Private Seller' }}</div>
                                <span class="seller-badge"><svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Verified</span>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;padding:0.875rem 0;border-top:1px solid #f0ece4;border-bottom:1px solid #f0ece4;margin-bottom:0.875rem;text-align:center;">
                            <div><span class="seller-stat-num">12</span><span class="seller-stat-lbl">Props</span></div>
                            <div><span class="seller-stat-num">8</span><span class="seller-stat-lbl">Sold</span></div>
                            <div><span class="seller-stat-num">4.8</span><span class="seller-stat-lbl">Rating</span></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.375rem;margin-bottom:0.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <svg width="13" height="13" viewBox="0 0 20 20" fill="{{ $i <= 5 ? '#c9a96e' : '#e0d5c5' }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span style="font-size:0.72rem;color:#8c8070;font-weight:300;">(24 reviews)</span>
                        </div>
                        <button class="seller-call-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>Call Now</button>
                        <button class="seller-msg-btn"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Send Message</button>
                    </div>

                    {{-- Safety tips --}}
                    <div class="safety-card">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;">
                            <div style="width:1.5rem;height:1px;background:#c9a96e;"></div>
                            <span style="font-size:0.62rem;letter-spacing:0.14em;text-transform:uppercase;color:#c9a96e;font-weight:600;">Safety Tips</span>
                        </div>
                        @foreach(['Inspect the property in person before making any payment','Verify all ownership documents with a legal expert','Never pay in cash or via untraceable methods'] as $i => $tip)
                            <div class="safety-item {{ $i > 0 ? 'mt-3' : '' }}">
                                <span class="safety-num">{{ $i + 1 }}</span>{{ $tip }}
                            </div>
                        @endforeach
                    </div>

                    {{-- Recently Viewed --}}
                    <div class="rv-card">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.875rem;">
                            <div style="width:1.5rem;height:1px;background:#c9a96e;"></div>
                            <span style="font-size:0.62rem;letter-spacing:0.14em;text-transform:uppercase;color:#c9a96e;font-weight:600;">Recently Viewed</span>
                        </div>
                        @forelse($recentlyViewed as $recent)
                            <a href="{{ route('buyer.properties.show', $recent->id) }}" class="rv-item">
                                <div class="rv-thumb"><img src="{{ $recent->image ? asset('storage/'.$recent->image) : asset('images/image1.jpg') }}" alt="{{ $recent->title }}"></div>
                                <div style="flex:1;min-width:0;">
                                    <div class="rv-title">{{ $recent->title }}</div>
                                    <div class="rv-loc">{{ $recent->location }}</div>
                                    <div class="rv-price">Rs {{ number_format($recent->price) }}</div>
                                </div>
                            </a>
                        @empty
                            <div style="text-align:center;padding:1.5rem 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="rgba(201,169,110,0.35)" stroke-width="1.25" style="display:block;margin:0 auto 0.625rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p style="font-size:0.78rem;color:#b0a090;font-weight:300;">No recently viewed properties</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>

        {{-- Recommendations --}}
        @if(isset($recommendations) && $recommendations->count())
            <div style="margin-top:4rem;">
                <hr class="gold-rule" style="margin-bottom:2rem;">
                <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:0.75rem;">
                    <div>
                        <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.375rem;">
                            <div style="width:1.5rem;height:1px;background:#c9a96e;"></div>
                            <span style="font-size:0.65rem;letter-spacing:0.14em;text-transform:uppercase;color:#c9a96e;font-weight:600;">More Like This</span>
                        </div>
                        <h2 style="font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:600;color:#0f0f0f;">Similar Properties</h2>
                    </div>
                    <a href="{{ route('buyer.properties', ['type' => $property->type]) }}" style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#0f0f0f;text-decoration:none;border-bottom:1.5px solid #c9a96e;padding-bottom:2px;">View All →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendations as $rec)
                        <a href="{{ route('buyer.properties.show', $rec->id) }}" class="rec-card">
                            <div class="rec-img">
                                <img src="{{ $rec->image_url ?? asset('images/image1.jpg') }}" alt="{{ $rec->title }}" loading="lazy">
                                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(10,8,5,0.4) 0%,transparent 55%);"></div>
                                <div style="position:absolute;top:0.75rem;left:0.75rem;">
                                    <span style="font-size:0.58rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;padding:0.225rem 0.55rem;border-radius:2px;background:#c9a96e;color:#0f0f0f;">{{ ucfirst($rec->purpose) }}</span>
                                </div>
                            </div>
                            <div class="rec-body">
                                <div class="rec-title">{{ $rec->title }}</div>
                                <div class="rec-loc">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $rec->location }}
                                </div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.5rem;">
                                    <div class="rec-price">Rs {{ number_format($rec->price) }}</div>
                                    @if($rec->area)<span style="font-size:0.72rem;color:#b0a090;font-weight:300;">{{ number_format($rec->area) }} sqft</span>@endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <button class="lb-close" onclick="closeLightbox()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
    <button class="lb-btn" style="left:1.5rem;" onclick="previousImage()">&#8592;</button>
    <img id="lightboxImage" src="" alt="">
    <button class="lb-btn" style="right:1.5rem;" onclick="nextImage()">&#8594;</button>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@push('scripts')
<script>
let map, currentIdx = 0;

// Safe coordinates
const propLat = {{ $property->latitude ?? 'null' }};
const propLng = {{ $property->longitude ?? 'null' }};

// Safe images array
const images = [
    @if($property->image_url)
        '{{ $property->image_url }}',
    @endif
    @if($property->gallery ?? false)
        @foreach($property->gallery as $img)
            '{{ $img }}',
        @endforeach
    @endif
].filter(Boolean);

/* ================= MAP ================= */
function initMap() {
    if (!propLat || !propLng) {
        document.getElementById('propertyMap').innerHTML =
            "<p style='padding:1rem;text-align:center;'>Location not available</p>";
        return;
    }

    map = L.map('propertyMap').setView([propLat, propLng], 15);

    addTileLayer('street');

    const icon = L.divIcon({
        className:'',
        html:`<div style="width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);background:#c9a96e;display:flex;align-items:center;justify-content:center;">
                <span style="transform:rotate(45deg);font-size:16px;">🏠</span>
              </div>`,
        iconSize:[36,36],
        popupAnchor:[0,-20]
    });

    L.marker([propLat, propLng], { icon }).addTo(map)
        .bindPopup(`<b>{{ $property->title }}</b><br>{{ $property->location }}<br>Rs {{ number_format($property->price) }}`)
        .openPopup();
}

function addTileLayer(type) {
    if (!map) return;

    map.eachLayer(l => {
        if (l instanceof L.TileLayer) map.removeLayer(l);
    });

    const tiles = {
        street: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        satellite: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        terrain: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png'
    };

    L.tileLayer(tiles[type], { maxZoom: 19 }).addTo(map);
}

function setMapType(type, btn) {
    addTileLayer(type);
    document.querySelectorAll('.map-type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function zoomToLocation() {
    if (map) map.setView([propLat, propLng], 18);
}

function getDirections() {
    if (!propLat || !propLng) return;
    window.open(`https://www.google.com/maps/dir/?api=1&destination=${propLat},${propLng}`, '_blank');
}

/* ================= IMAGE GALLERY ================= */
function changeImage(idx) {
    if (!images.length) return;

    currentIdx = idx;

    const mainImg = document.getElementById('mainPropertyImage');
    if (mainImg) mainImg.src = images[idx];

    const ci = document.getElementById('currentImageIndex');
    if (ci) ci.textContent = idx + 1;

    document.querySelectorAll('.gallery-thumb').forEach((t,i) => {
        t.classList.toggle('active', i === idx);
    });
}

/* ================= LIGHTBOX ================= */
function openLightbox() {
    if (!images.length) return;

    document.getElementById('lightboxImage').src = images[currentIdx];
    document.getElementById('lightbox').classList.add('open');
}

function closeLightbox(e) {
    if (!e || e.target.id === 'lightbox') {
        document.getElementById('lightbox').classList.remove('open');
    }
}

function nextImage() {
    if (!images.length) return;

    currentIdx = (currentIdx + 1) % images.length;
    document.getElementById('lightboxImage').src = images[currentIdx];
}

function previousImage() {
    if (!images.length) return;

    currentIdx = (currentIdx - 1 + images.length) % images.length;
    document.getElementById('lightboxImage').src = images[currentIdx];
}

/* ================= DESCRIPTION ================= */
function toggleDesc() {
    const more = document.getElementById('descMore');
    const btn = document.getElementById('descBtn');

    if (!more) return;

    more.classList.toggle('hidden');
    btn.textContent = more.classList.contains('hidden') ? 'Read More ↓' : 'Read Less ↑';
}

/* ================= SHARE ================= */
function shareOnFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(location.href), '_blank');
}

function shareOnTwitter() {
    window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(location.href), '_blank');
}

function copyToClipboard() {
    navigator.clipboard.writeText(location.href)
        .then(() => alert('Link copied!'));
}

/* ================= KEYBOARD ================= */
document.addEventListener('keydown', e => {
    const lb = document.getElementById('lightbox');

    if (lb.classList.contains('open')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') previousImage();
    }
});

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('propertyMap')) {
        initMap();
    }
});
</script>
@endpush

</x-app-layout>