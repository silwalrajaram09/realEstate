@props(['property', 'showScore' => false])

<style>
    .pc-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        overflow: hidden;
        position: relative;
        transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
    }

    .pc-card:hover {
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.09);
        transform: translateY(-3px);
        border-color: transparent;
    }

    .pc-img-wrap {
        position: relative;
        aspect-ratio: 4/3;
        overflow: hidden;
        background: #e8e0d4;
    }

    .pc-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .pc-card:hover .pc-img-wrap img {
        transform: scale(1.05);
    }

    .pc-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 15, 15, 0.4) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pc-card:hover .pc-img-overlay {
        opacity: 1;
    }

    .pc-badge {
        font-size: 0.58rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 0.225rem 0.55rem;
        border-radius: 2px;
        display: inline-block;
    }

    .pc-badge-sale {
        background: #c9a96e;
        color: #0f0f0f;
    }

    .pc-badge-rent {
        background: #0f0f0f;
        color: #c9a96e;
        border: 1px solid #c9a96e;
    }

    .pc-badge-new {
        background: rgba(255, 255, 255, 0.92);
        color: #0f0f0f;
    }

    .pc-score {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: #c9a96e;
        color: #0f0f0f;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 0.25rem 0.625rem;
        border-radius: 2px;
        font-family: 'Outfit', sans-serif;
    }

    .pc-fav {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: background 0.2s ease;
        z-index: 2;
        opacity: 0;
        transition: opacity 0.25s ease, background 0.2s ease;
    }

    .pc-card:hover .pc-fav {
        opacity: 1;
    }

    .pc-fav:hover {
        background: #fff;
    }

    .pc-fav svg {
        transition: stroke 0.2s ease;
    }

    .pc-fav:hover svg {
        stroke: #c0392b;
    }

    .pc-price {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        background: rgba(250, 247, 242, 0.96);
        backdrop-filter: blur(4px);
        border-radius: 3px;
        padding: 0.25rem 0.75rem;
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.15rem;
        font-weight: 600;
        color: #0f0f0f;
        line-height: 1.25;
    }

    .pc-price .pm {
        font-family: 'Outfit', sans-serif;
        font-size: 0.6rem;
        color: #8c8070;
        font-weight: 400;
        display: block;
        text-align: right;
    }

    .pc-body {
        padding: 1.125rem 1.25rem 1.375rem;
    }

    .pc-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f0f0f;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-decoration: none;
        display: block;
        transition: color 0.2s ease;
        line-height: 1.2;
    }

    .pc-title:hover {
        color: #c9a96e;
    }

    .pc-location {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.775rem;
        font-weight: 300;
        color: #8c8070;
        margin-top: 0.25rem;
        overflow: hidden;
        white-space: nowrap;
    }

    .pc-location span {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .pc-specs {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border: 1px solid #f0ece4;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 0.875rem;
    }

    .pc-spec {
        text-align: center;
        padding: 0.5rem 0.25rem;
        border-right: 1px solid #f0ece4;
    }

    .pc-spec:last-child {
        border-right: none;
    }

    .pc-spec-label {
        font-size: 0.58rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b0a090;
        font-weight: 500;
        display: block;
    }

    .pc-spec-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f0f0f;
        display: block;
        margin-top: 0.1rem;
    }

    .pc-features {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
        margin-top: 0.75rem;
    }

    .pc-feature-tag {
        font-size: 0.65rem;
        font-weight: 400;
        color: #6b5e52;
        background: #f4ede3;
        padding: 0.2rem 0.5rem;
        border-radius: 2px;
    }

    .pc-cta {
        display: block;
        text-align: center;
        margin-top: 1rem;
        padding: 0.625rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #0f0f0f;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
    }

    .pc-cta:hover {
        background: #c9a96e;
        border-color: #c9a96e;
    }
</style>

<div class="pc-card">

    {{-- Fav button --}}
    {{-- Favorite Button --}}
    <button class="pc-fav favorite-btn" data-property-id="{{ $property->id }}"
        onclick="toggleFavorite({{ $property->id }})" aria-label="Save">

        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" stroke-width="1.75"
            fill="{{ $isFavorited ? '#c0392b' : 'none' }}" stroke="{{ $isFavorited ? '#c0392b' : '#3a3028' }}">

            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>


    {{-- Match score --}}
    @if($showScore && isset($property->relevance_score))
        <div class="pc-score">{{ round($property->relevance_score) }}% Match</div>
    @endif

    {{-- Image --}}
    <div class="pc-img-wrap">
        <img src="{{ $property->image_url }}" alt="{{ $property->title }}" loading="lazy">
        <div class="pc-img-overlay"></div>

        {{-- Badges --}}
        <div
            style="position:absolute; bottom:0.75rem; left:0.75rem; display:flex; gap:0.3rem; flex-wrap:wrap; z-index:2;">
            <span
                class="pc-badge {{ $property->purpose === 'buy' || $property->purpose === 'sale' ? 'pc-badge-sale' : 'pc-badge-rent' }}">
                {{ $property->purpose === 'buy' || $property->purpose === 'sale' ? 'For Sale' : 'For Rent' }}
            </span>
            @if(isset($property->created_at) && $property->created_at->diffInDays(now()) < 7)
                <span class="pc-badge pc-badge-new">New</span>
            @endif
        </div>

        {{-- Price --}}
        <div class="pc-price">
            Rs {{ number_format($property->price) }}
            @if($property->purpose === 'rent')
                <span class="pm">/month</span>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="pc-body">
        <a href="{{ route('buyer.properties.show', $property->id) }}" class="pc-title">
            {{ $property->title }}
        </a>

        <div class="pc-location">
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>{{ $property->location }}</span>
        </div>

        {{-- Specs --}}
        @if($property->bedrooms || $property->bathrooms || $property->area)
            <div class="pc-specs">
                @if($property->bedrooms)
                    <div class="pc-spec">
                        <span class="pc-spec-label">Beds</span>
                        <span class="pc-spec-value">{{ $property->bedrooms }}</span>
                    </div>
                @endif
                @if($property->bathrooms)
                    <div class="pc-spec">
                        <span class="pc-spec-label">Baths</span>
                        <span class="pc-spec-value">{{ $property->bathrooms }}</span>
                    </div>
                @endif
                @if($property->area)
                    <div class="pc-spec">
                        <span class="pc-spec-label">Area</span>
                        <span class="pc-spec-value" style="font-size:0.775rem;">{{ number_format($property->area) }}<span
                                style="font-size:0.58rem; color:#8c8070;"> sqft</span></span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Feature tags --}}
        @if(isset($property->features) && is_array($property->features) && count($property->features) > 0)
            <div class="pc-features">
                @foreach(array_slice($property->features, 0, 3) as $f)
                    <span class="pc-feature-tag">{{ $f }}</span>
                @endforeach
                @if(count($property->features) > 3)
                    <span class="pc-feature-tag">+{{ count($property->features) - 3 }}</span>
                @endif
            </div>
        @endif

        <a href="{{ route('buyer.properties.show', $property->id) }}" class="pc-cta">View Details</a>
    </div>
</div>


<script>
    function toggleFavorite(propertyId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Find all buttons for this property (in case there are multiple on the page)
        const buttons = document.querySelectorAll(`.favorite-btn[data-property-id="${propertyId}"]`);

        fetch(`/buyer/favorites/${propertyId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (response.status === 401) {
                    // Show login modal or redirect
                    if (confirm('Please login to save favorites. Go to login page?')) {
                        window.location.href = '/login';
                    }
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;

                // Update all buttons for this property on the page
                buttons.forEach(btn => {
                    const svg = btn.querySelector('svg');

                    if (data.is_favorite) {
                        svg.setAttribute('fill', '#c0392b');
                        svg.setAttribute('stroke', '#c0392b');
                        btn.classList.add('favorited');
                    } else {
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', '#3a3028');
                        btn.classList.remove('favorited');
                    }
                });

                // Show toast notification if available
                if (typeof window.showToast === 'function') {
                    window.showToast(data.message);
                } else {
                    // Simple feedback
                    console.log(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>