<x-app-layout>

<style>
    .fav-root { font-family: 'Outfit', sans-serif; }

    /* ── Header ── */
    .fav-header {
        position: relative;
        overflow: hidden;
        background: #0f0f0f;
        border-radius: 4px;
        padding: 3rem 3.5rem;
        margin-bottom: 2.5rem;
    }
    .fav-header::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(201,169,110,0.06) 39px, rgba(201,169,110,0.06) 40px),
            repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(201,169,110,0.06) 39px, rgba(201,169,110,0.06) 40px);
        pointer-events: none;
    }
    .fav-header::after {
        content: '';
        position: absolute; top: -4rem; right: -4rem;
        width: 18rem; height: 18rem; border-radius: 50%;
        background: radial-gradient(circle, rgba(201,169,110,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .fav-eyebrow { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.75rem; }
    .fav-eyebrow-line { width: 1.5rem; height: 1px; background: #c9a96e; }
    .fav-eyebrow span { font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: #c9a96e; font-weight: 600; }
    .fav-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: clamp(2rem, 4vw, 3rem); font-weight: 600;
        color: #fff; line-height: 1.1; margin-bottom: 0.625rem; position: relative;
    }
    .fav-header h1 em { color: #c9a96e; font-style: italic; }
    .fav-header p { font-size: 0.875rem; font-weight: 300; color: rgba(255,255,255,0.5); position: relative; }
    .fav-header-count {
        position: absolute; top: 2.5rem; right: 3.5rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 3.5rem; font-weight: 300; line-height: 1;
        color: rgba(201,169,110,0.25); letter-spacing: -0.02em;
    }
    .fav-count-label {
        font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase;
        color: rgba(255,255,255,0.3); font-weight: 500;
        display: block; text-align: right;
    }

    /* ── Toolbar ── */
    .fav-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        margin-bottom: 1.75rem;
    }
    .fav-count-pill {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.75rem;
        background: rgba(201,169,110,0.08);
        border: 1px solid rgba(201,169,110,0.25);
        border-radius: 2px;
        font-size: 0.65rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase;
        color: #9a7340;
    }
    .fav-heart-dot {
        width: 6px; height: 6px; border-radius: 50%; background: #c9a96e;
    }

    /* ── Cards ── */
    .fav-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        overflow: hidden;
        transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
        display: flex; flex-direction: column;
        animation: fadeUp 0.4s ease both;
    }
    .fav-card:hover {
        border-color: #c9a96e;
        transform: translateY(-3px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.07);
    }
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }

    .fav-img {
        position: relative; height: 13rem;
        background: #f5f0e8; overflow: hidden;
    }
    .fav-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .fav-card:hover .fav-img img { transform: scale(1.04); }

    .fav-badge {
        position: absolute; top: 0.75rem; left: 0.75rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.62rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        border-radius: 2px;
    }
    .fav-badge-sale { background: #c9a96e; color: #0f0f0f; }
    .fav-badge-rent { background: #0f0f0f; color: #c9a96e; border: 1px solid #c9a96e; }

    .fav-remove-btn {
        position: absolute; top: 0.75rem; right: 0.75rem;
        width: 2rem; height: 2rem; border-radius: 50%;
        background: rgba(255,255,255,0.92);
        border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s ease;
        z-index: 2;
    }
    .fav-remove-btn:hover { background: #fff; }
    .fav-remove-btn:hover svg { stroke: #c0392b; fill: #c0392b; }
    .fav-remove-btn svg { transition: stroke 0.2s, fill 0.2s; }

    .fav-price {
        position: absolute; bottom: 0.75rem; right: 0.75rem;
        background: rgba(250,247,242,0.96);
        border-radius: 3px; padding: 0.25rem 0.75rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.15rem; font-weight: 600; color: #0f0f0f; line-height: 1.25;
    }
    .fav-price .pm {
        font-family: 'Outfit', sans-serif; font-size: 0.6rem;
        color: #8c8070; font-weight: 400; display: block; text-align: right;
    }

    .fav-body { padding: 1.125rem 1.25rem 1.375rem; flex: 1; display: flex; flex-direction: column; }
    .fav-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem; font-weight: 600; color: #0f0f0f;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        text-decoration: none; display: block;
        transition: color 0.2s ease; line-height: 1.2;
    }
    .fav-title:hover { color: #c9a96e; }

    .fav-loc {
        display: flex; align-items: center; gap: 0.3rem;
        font-size: 0.775rem; font-weight: 300; color: #8c8070;
        margin-top: 0.25rem; overflow: hidden; white-space: nowrap;
    }
    .fav-loc span { overflow: hidden; text-overflow: ellipsis; }

    .fav-specs {
        display: grid; grid-template-columns: repeat(3, 1fr);
        border: 1px solid #f0ece4; border-radius: 3px;
        overflow: hidden; margin-top: 0.875rem;
    }
    .fav-spec {
        text-align: center; padding: 0.5rem 0.25rem;
        border-right: 1px solid #f0ece4;
    }
    .fav-spec:last-child { border-right: none; }
    .fav-spec-label {
        font-size: 0.58rem; letter-spacing: 0.1em; text-transform: uppercase;
        color: #b0a090; font-weight: 500; display: block;
    }
    .fav-spec-value { font-size: 0.875rem; font-weight: 600; color: #0f0f0f; display: block; margin-top: 0.1rem; }

    .fav-saved-on {
        font-size: 0.7rem; color: #b0a090; font-weight: 300;
        margin-top: 0.625rem;
    }

    .fav-cta {
        display: block; text-align: center;
        margin-top: auto; padding-top: 0.875rem;
        padding: 0.625rem;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        color: #0f0f0f; background: #faf7f2;
        border: 1px solid #ddd5c8; border-radius: 3px;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
        margin-top: 1rem;
    }
    .fav-cta:hover { background: #c9a96e; border-color: #c9a96e; }

    /* ── Empty state ── */
    .fav-empty {
        grid-column: 1 / -1;
        display: flex; flex-direction: column; align-items: center;
        text-align: center; padding: 5rem 2rem;
        background: #fff; border: 1px solid #ede8df; border-radius: 4px;
    }
    .fav-empty-icon {
        width: 5rem; height: 5rem; border-radius: 50%;
        background: rgba(201,169,110,0.07);
        border: 1px solid rgba(201,169,110,0.2);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.5rem;
    }
    .fav-empty-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.75rem; font-weight: 600; color: #0f0f0f; margin-bottom: 0.625rem;
    }
    .fav-empty-sub {
        font-size: 0.875rem; font-weight: 300; color: #8c8070;
        max-width: 26rem; margin: 0 auto 2rem; line-height: 1.7;
    }
    .fav-empty-cta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.8rem 2rem; background: #c9a96e; color: #0f0f0f;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
        border-radius: 2px; text-decoration: none; transition: background 0.2s;
    }
    .fav-empty-cta:hover { background: #b5924f; }

    /* ── Toast ── */
    .fav-toast {
        position: fixed; bottom: 1.5rem; right: 1.5rem;
        padding: 0.75rem 1.25rem;
        background: #0f0f0f; color: #fff;
        font-size: 0.8rem; font-weight: 500;
        border-radius: 3px; border-left: 3px solid #c9a96e;
        opacity: 0; transform: translateY(8px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        z-index: 999; pointer-events: none;
    }
    .fav-toast.show { opacity: 1; transform: translateY(0); }
</style>

<div class="fav-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── HEADER ── --}}
    <div class="fav-header">
        <div class="fav-eyebrow">
            <div class="fav-eyebrow-line"></div>
            <span>Saved</span>
        </div>
        <h1>My <em>Favourites</em></h1>
        <p>Properties you've saved — remove any time by clicking the heart.</p>

        <div class="fav-header-count">
            <span class="fav-count-number">{{ $favorites->count() }}</span>
            <span class="fav-count-label">saved listings</span>
        </div>
    </div>

    {{-- ── TOOLBAR ── --}}
    @if($favorites->isNotEmpty())
    <div class="fav-toolbar">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
            <div class="fav-count-pill">
                <div class="fav-heart-dot"></div>
                {{ $favorites->count() }} saved {{ Str::plural('property', $favorites->count()) }}
            </div>
        </div>
        <a href="{{ route('buyer.properties') }}"
           style="font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;
                  color:#8c8070; text-decoration:none; padding:0.45rem 0.875rem;
                  border:1px solid #ede8df; border-radius:3px; background:#fff;
                  transition: border-color 0.2s, color 0.2s;"
           onmouseover="this.style.borderColor='#c9a96e';this.style.color='#9a7340'"
           onmouseout="this.style.borderColor='#ede8df';this.style.color='#8c8070'">
            Browse more
        </a>
    </div>
    @endif

    {{-- ── GRID ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="fav-grid">

        @forelse($favorites as $i => $favorite)
            @php $property = $favorite->property; @endphp
            <div class="fav-card" id="fav-card-{{ $property->id }}" style="animation-delay: {{ $i * 0.06 }}s">

                {{-- Image --}}
                <div class="fav-img">
                    <img src="{{ $property->image_url }}" alt="{{ $property->title }}" loading="lazy">

                    <span class="fav-badge {{ $property->purpose === 'buy' ? 'fav-badge-sale' : 'fav-badge-rent' }}">
                        {{ $property->purpose === 'buy' ? 'For Sale' : 'For Rent' }}
                    </span>

                    {{-- Remove (unfavourite) button --}}
                    <button class="fav-remove-btn" onclick="removeFavorite({{ $property->id }})" title="Remove from favourites">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             stroke-width="1.75" fill="#c0392b" stroke="#c0392b">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>

                    {{-- Price --}}
                    <div class="fav-price">
                        Rs {{ number_format($property->price) }}
                        @if($property->purpose === 'rent')
                            <span class="pm">/month</span>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="fav-body">
                    <a href="{{ route('buyer.properties.show', $property->id) }}" class="fav-title">
                        {{ $property->title }}
                    </a>

                    <div class="fav-loc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $property->location }}</span>
                    </div>

                    @if($property->bedrooms || $property->bathrooms || $property->area)
                        <div class="fav-specs">
                            @if($property->bedrooms)
                                <div class="fav-spec">
                                    <span class="fav-spec-label">Beds</span>
                                    <span class="fav-spec-value">{{ $property->bedrooms }}</span>
                                </div>
                            @endif
                            @if($property->bathrooms)
                                <div class="fav-spec">
                                    <span class="fav-spec-label">Baths</span>
                                    <span class="fav-spec-value">{{ $property->bathrooms }}</span>
                                </div>
                            @endif
                            @if($property->area)
                                <div class="fav-spec">
                                    <span class="fav-spec-label">Area</span>
                                    <span class="fav-spec-value" style="font-size:0.775rem;">
                                        {{ number_format($property->area) }}<span style="font-size:0.58rem;color:#8c8070;"> sqft</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="fav-saved-on">
                        Saved {{ $favorite->created_at->diffForHumans() }}
                    </div>

                    <a href="{{ route('buyer.properties.show', $property->id) }}" class="fav-cta">
                        View Details
                    </a>
                </div>

            </div>

        @empty
            <div class="fav-empty">
                <div class="fav-empty-icon">
                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.25">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div class="fav-empty-title">No Saved Properties</div>
                <p class="fav-empty-sub">
                    Browse listings and tap the heart icon to save properties you love. They'll appear here for easy comparison.
                </p>
                <a href="{{ route('buyer.properties') }}" class="fav-empty-cta">
                    Browse Properties
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endforelse

    </div>

</div>

{{-- Toast notification --}}
<div class="fav-toast" id="fav-toast"></div>

<script>
function showToast(message) {
    const toast = document.getElementById('fav-toast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2800);
}

function removeFavorite(propertyId) {
    fetch(`/buyer/favorites/${propertyId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.is_favorite) {
            // Animate card out then remove it from the DOM
            const card = document.getElementById(`fav-card-${propertyId}`);
            if (card) {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity   = '0';
                card.style.transform = 'scale(0.96)';
                setTimeout(() => {
                    card.remove();
                    updateCount(data.count);

                    // Show empty state if no cards remain
                    const grid = document.getElementById('fav-grid');
                    if (grid && grid.querySelectorAll('.fav-card').length === 0) {
                        location.reload(); // reload to render the empty state blade
                    }
                }, 300);
            }
            showToast('Removed from favourites');
        }
    })
    .catch(() => showToast('Something went wrong — please try again'));
}

function updateCount(newCount) {
    // Update header ghost number — use a dedicated span to avoid firstChild node issues
    const headerCount = document.querySelector('.fav-count-number');
    if (headerCount) headerCount.textContent = newCount;

    // Update toolbar pill
    const pill = document.querySelector('.fav-count-pill');
    if (pill) {
        const label = newCount === 1 ? 'property' : 'properties';
        pill.innerHTML = `<div class="fav-heart-dot"></div> ${newCount} saved ${label}`;
    }

    // Hide toolbar if 0 left
    if (newCount === 0) {
        const toolbar = document.querySelector('.fav-toolbar');
        if (toolbar) toolbar.style.display = 'none';
    }
}
</script>

</x-app-layout>