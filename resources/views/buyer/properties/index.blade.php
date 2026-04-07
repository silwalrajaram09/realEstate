<x-app-layout>

<style>
    [x-cloak] { display: none !important; }
    .browse-root { font-family: 'Outfit', sans-serif; }

    /* Sidebar */
    .filter-sidebar {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        padding: 1.75rem;
        position: sticky;
        top: 5.5rem;
    }
    .filter-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f0f0f;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .filter-reset {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #c9a96e;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .filter-reset:hover { color: #9a7340; }

    .filter-group { margin-bottom: 1.375rem; }
    .filter-label {
        font-size: 0.65rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #8c8070;
        font-weight: 600;
        display: block;
        margin-bottom: 0.625rem;
    }
    .filter-input, .filter-select {
        font-family: 'Outfit', sans-serif;
        font-size: 0.8125rem;
        font-weight: 400;
        color: #0f0f0f;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.625rem 0.875rem;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #c9a96e;
        box-shadow: 0 0 0 3px rgba(201,169,110,0.1);
        background: #fff;
    }
    .filter-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
        cursor: pointer;
    }

    /* Radio pill group */
    .pill-group { display: flex; flex-wrap: wrap; gap: 0.375rem; }
    .pill-label { cursor: pointer; }
    .pill-label input { position: absolute; opacity: 0; pointer-events: none; }
    .pill-span {
        display: block;
        padding: 0.3rem 0.75rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: #4a4038;
        border: 1px solid #ddd5c8;
        border-radius: 2px;
        background: #faf7f2;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .pill-label input:checked + .pill-span {
        background: #c9a96e;
        border-color: #c9a96e;
        color: #0f0f0f;
        font-weight: 600;
    }
    .pill-span:hover { border-color: #c9a96e; }

    /* Checkbox */
    .check-item { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.25rem 0; }
    .check-item input[type="checkbox"] {
        width: 14px; height: 14px;
        border: 1.5px solid #ddd5c8;
        border-radius: 2px;
        accent-color: #c9a96e;
        cursor: pointer;
    }
    .check-item-label { font-size: 0.8125rem; color: #4a4038; font-weight: 400; }

    .filter-sep { height: 1px; background: #f0ece4; margin: 1.375rem 0; }

    .filter-submit {
        width: 100%;
        padding: 0.75rem;
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
    }
    .filter-submit:hover { background: #b5924f; }

    /* Active filter chip */
    .filter-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.625rem;
        background: rgba(201,169,110,0.1);
        border: 1px solid rgba(201,169,110,0.3);
        border-radius: 2px;
        font-size: 0.72rem;
        font-weight: 500;
        color: #6b5e52;
    }
    .filter-chip a { color: #9a7340; text-decoration: none; line-height: 1; font-size: 0.9rem; }
    .filter-chip a:hover { color: #c0392b; }

    /* Quick price links */
    .qp-link {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid #ddd5c8;
        border-radius: 2px;
        color: #4a4038;
        text-decoration: none;
        background: #faf7f2;
        transition: all 0.2s ease;
    }
    .qp-link:hover, .qp-link.active {
        background: rgba(201,169,110,0.12);
        border-color: #c9a96e;
        color: #9a7340;
    }

    /* Results header */
    .results-header {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .results-count {
        font-size: 0.8rem; font-weight: 300; color: #8c8070;
    }
    .sort-dropdown {
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
    .sort-dropdown:focus { outline: none; border-color: #c9a96e; }

    .per-page-select {
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
    .per-page-select:focus { outline: none; border-color: #c9a96e; }

    /* Location button */
    .loc-btn {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.625rem 0.875rem;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        font-family: 'Outfit', sans-serif;
        font-size: 0.8rem; font-weight: 500; color: #4a4038;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
    }
    .loc-btn:hover { border-color: #c9a96e; background: #fff; }
    .skeleton-card {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        overflow: hidden;
    }
    .skeleton-shimmer {
        background: linear-gradient(90deg, #f3efe7 25%, #ece6db 37%, #f3efe7 63%);
        background-size: 400% 100%;
        animation: shimmer 1.4s ease infinite;
    }
    @keyframes shimmer {
        0% { background-position: 100% 0; }
        100% { background-position: 0 0; }
    }
</style>

<div class="browse-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10" x-data="{loading:true}" x-init="setTimeout(() => loading=false, 350)">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.375rem;">
                <div style="width:1.5rem; height:1px; background:#c9a96e;"></div>
                <span style="font-size:0.65rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e; font-weight:600;">Browse</span>
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(1.75rem,3.5vw,2.5rem); font-weight:600; color:#0f0f0f; line-height:1.1;">
                All <em style="color:#c9a96e; font-style:italic;">Properties</em>
            </h1>
            <p style="font-size:0.875rem; font-weight:300; color:#8c8070; margin-top:0.375rem;">
                {{ $properties->total() ?? $properties->count() }} listings available
            </p>
        </div>

        {{-- Active filter chips --}}
        @php $hasFilters = request()->hasAny(['purpose','type','category','q','min_price','max_price','bedrooms']); @endphp
        @if($hasFilters)
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center;">
                @if(request('purpose'))
                    <span class="filter-chip">{{ ucfirst(request('purpose')) }} <a href="{{ request()->fullUrlWithQuery(['purpose' => null]) }}">×</a></span>
                @endif
                @if(request('type'))
                    <span class="filter-chip">{{ ucfirst(request('type')) }} <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}">×</a></span>
                @endif
                @if(request('q'))
                    <span class="filter-chip">"{{ request('q') }}" <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}">×</a></span>
                @endif
                @if(request('min_price') || request('max_price'))
                    <span class="filter-chip">Price filtered <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}">×</a></span>
                @endif
                <a href="{{ route('buyer.properties') }}"
                   style="font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#c0392b; text-decoration:none;">
                    Clear all
                </a>
            </div>
        @endif
    </div>

    <div style="display:flex; gap:2rem; align-items:flex-start;">

        {{-- ── SIDEBAR ── --}}
        <aside style="width:17rem; flex-shrink:0;" class="hidden lg:block">
            <div class="filter-sidebar">
                <div class="filter-title">
                    Filters
                    @if($hasFilters)
                        <a href="{{ route('buyer.properties') }}" class="filter-reset">Reset</a>
                    @endif
                </div>

                <form action="{{ route('buyer.properties') }}" method="GET"
                      x-data="{
                          purpose: '{{ request('purpose', '') }}',
                          type: '{{ request('type', '') }}',
                          category: '{{ request('category', '') }}',
                          isResidential() { return ['flat', 'house'].includes(this.type) || this.category === 'residential'; },
                          isLand() { return this.type === 'land'; },
                          isCommercialLike() { return ['commercial', 'office', 'warehouse'].includes(this.type) || ['commercial', 'industrial'].includes(this.category); }
                      }">

                    {{-- Search --}}
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <div style="position:relative;">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Title, location…" class="filter-input" style="padding-left:2.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#b0a090" stroke-width="1.75"
                                 style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="filter-sep"></div>

                    {{-- Purpose --}}
                    <div class="filter-group">
                        <label class="filter-label">Purpose</label>
                        <div class="pill-group">
                            @foreach(['buy' => 'Buy', 'rent' => 'Rent'] as $v => $l)
                                <label class="pill-label">
                                    <input type="radio" name="purpose" value="{{ $v }}" x-model="purpose" {{ request('purpose') === $v ? 'checked' : '' }}>
                                    <span class="pill-span">{{ $l }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Type --}}
                    <div class="filter-group">
                        <label class="filter-label">Property Type</label>
                        <select name="type" class="filter-select" x-model="type">
                            <option value="">All Types</option>
                            <option value="flat"       {{ request('type') === 'flat'       ? 'selected' : '' }}>Flat / Apartment</option>
                            <option value="house"      {{ request('type') === 'house'      ? 'selected' : '' }}>House</option>
                            <option value="land"       {{ request('type') === 'land'       ? 'selected' : '' }}>Land / Plot</option>
                            <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="office"     {{ request('type') === 'office'     ? 'selected' : '' }}>Office</option>
                            <option value="warehouse"  {{ request('type') === 'warehouse'  ? 'selected' : '' }}>Warehouse</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-select" x-model="category">
                            <option value="">All Categories</option>
                            <option value="residential"  {{ request('category') === 'residential'  ? 'selected' : '' }}>Residential</option>
                            <option value="commercial"   {{ request('category') === 'commercial'   ? 'selected' : '' }}>Commercial</option>
                            <option value="industrial"   {{ request('category') === 'industrial'   ? 'selected' : '' }}>Industrial</option>
                        </select>
                    </div>

                    <div class="filter-sep"></div>

                    {{-- Price --}}
                    <div class="filter-group">
                        <label class="filter-label">Price Range (NPR)</label>
                        <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:0.5rem;">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="filter-input" style="width:50%;">
                            <span style="color:#b0a090; flex-shrink:0;">–</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="filter-input" style="width:50%;">
                        </div>
                        <div style="display:flex; flex-wrap:wrap; gap:0.375rem; margin-top:0.5rem;">
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => 5000000]) }}"
                               class="qp-link {{ request('max_price') == 5000000 && !request('min_price') ? 'active' : '' }}">Under 50L</a>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => 5000000, 'max_price' => 10000000]) }}"
                               class="qp-link {{ request('min_price') == 5000000 && request('max_price') == 10000000 ? 'active' : '' }}">50L–1Cr</a>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => 10000000, 'max_price' => null]) }}"
                               class="qp-link {{ request('min_price') == 10000000 && !request('max_price') ? 'active' : '' }}">1Cr+</a>
                        </div>
                    </div>

                    {{-- Bedrooms (residential only) --}}
                    <div class="filter-group" x-show="isResidential()" x-cloak>
                        <label class="filter-label">Bedrooms</label>
                        <div class="pill-group">
                            @foreach(['' => 'Any', '1' => '1+', '2' => '2+', '3' => '3+', '4' => '4+', '5' => '5+'] as $v => $l)
                                <label class="pill-label">
                                    <input type="radio" name="bedrooms" value="{{ $v }}" {{ request('bedrooms') === $v ? 'checked' : '' }}>
                                    <span class="pill-span">{{ $l }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-group" x-show="isResidential() || isLand() || isCommercialLike()" x-cloak>
                        <label class="filter-label">Area Range (sq ft)</label>
                        <div style="display:flex; gap:0.5rem;">
                            <input type="number" name="min_area" value="{{ request('min_area') }}" placeholder="Min" class="filter-input">
                            <input type="number" name="max_area" value="{{ request('max_area') }}" placeholder="Max" class="filter-input">
                        </div>
                    </div>

                    {{-- Bathrooms (residential/commercial only) --}}
                    <div class="filter-group" x-show="isResidential() || isCommercialLike()" x-cloak>
                        <label class="filter-label">Bathrooms</label>
                        <div class="pill-group">
                            @foreach(['' => 'Any', '1' => '1+', '2' => '2+', '3' => '3+'] as $v => $l)
                                <label class="pill-label">
                                    <input type="radio" name="bathrooms" value="{{ $v }}" {{ request('bathrooms') === $v ? 'checked' : '' }}>
                                    <span class="pill-span">{{ $l }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-sep"></div>

                    {{-- Location GPS --}}
                    <div class="filter-group">
                        <label class="filter-label">Location</label>
                        <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                        <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">
                        <input type="number" name="radius" value="{{ request('radius', 10) }}" min="1" max="100" class="filter-input" style="margin-bottom:0.5rem;" placeholder="Radius km">
                        <button type="button" onclick="getCurrentLocation()" class="loc-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span id="location-text">Use my location</span>
                        </button>
                        @if(request('lat') && request('lng'))
                            <p style="font-size:0.7rem; color:#5a8a5a; margin-top:0.4rem; display:flex; align-items:center; gap:0.3rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Location set &nbsp;
                                <a href="{{ route('buyer.properties') }}" style="color:#c0392b; text-decoration:none;">clear</a>
                            </p>
                        @endif
                    </div>

                    <button type="submit" class="filter-submit">Apply Filters</button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN ── --}}
        <main style="flex:1; min-width:0;">

            <div class="results-header">
                <span class="results-count">{{ $properties->total() }} properties found</span>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <select class="sort-dropdown"
                            onchange="window.location.href = updateQS(window.location.href,'sort',this.value)">
                        @php $sl = ['latest'=>'Most Recent','price_asc'=>'Price: Low→High','price_desc'=>'Price: High→Low']; @endphp
                        @foreach($sl as $v => $l)
                            <option value="{{ $v }}" {{ request('sort','latest') == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                    <select class="per-page-select"
                            onchange="window.location.href = updateQS(window.location.href,'per_page',this.value)">
                        @foreach([12,24,36] as $n)
                            <option value="{{ $n }}" {{ request('per_page',12) == $n ? 'selected' : '' }}>{{ $n }} / page</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="loading" x-cloak>
                <template x-for="i in 6" :key="'sk-'+i">
                    <div class="skeleton-card">
                        <div class="skeleton-shimmer h-44"></div>
                        <div class="p-4 space-y-3">
                            <div class="skeleton-shimmer h-4 rounded"></div>
                            <div class="skeleton-shimmer h-3 rounded w-3/4"></div>
                            <div class="skeleton-shimmer h-10 rounded"></div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-show="!loading" x-cloak>
                @forelse($properties as $property)
                    <x-property-card :property="$property" />
                @empty
                    <div class="col-span-full" style="text-align:center; padding:5rem 2rem; background:#fff; border:1px solid #ede8df; border-radius:4px;">
                        <div style="width:4rem; height:4rem; border-radius:50%; background:rgba(201,169,110,0.08); border:1px solid rgba(201,169,110,0.2); display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <p style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.5rem;">No Properties Found</p>
                        <p style="font-size:0.875rem; font-weight:300; color:#8c8070; max-width:22rem; margin:0 auto 1.5rem;">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('buyer.properties') }}"
                           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.75rem;
                                  background:#c9a96e; color:#0f0f0f; font-size:0.75rem; font-weight:700;
                                  letter-spacing:0.1em; text-transform:uppercase; border-radius:3px; text-decoration:none;">
                            Clear Filters
                        </a>
                    </div>
                @endforelse
            </div>

            @if($properties->hasPages())
                <div style="margin-top:3rem;">
                    {{ $properties->links() }}
                </div>
            @endif

        </main>
    </div>
</div>

@push('scripts')
<script>
function updateQS(uri, key, value) {
    const re = new RegExp('([?&])' + key + '=.*?(&|$)', 'i');
    const sep = uri.indexOf('?') !== -1 ? '&' : '?';
    return uri.match(re) ? uri.replace(re, '$1' + key + '=' + value + '$2') : uri + sep + key + '=' + value;
}
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            pos => {
                document.getElementById('lat').value = pos.coords.latitude.toFixed(6);
                document.getElementById('lng').value = pos.coords.longitude.toFixed(6);
                const el = document.getElementById('location-text');
                el.textContent = 'Location set!';
                el.style.color = '#5a8a5a';
            },
            err => alert('Unable to get location: ' + err.message)
        );
    }
}
</script>
@endpush

</x-app-layout>