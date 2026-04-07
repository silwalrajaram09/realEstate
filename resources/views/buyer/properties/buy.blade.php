<x-app-layout>

{{-- Reuses the same filter/browse UI as index.blade.php but locked to purpose=buy --}}

<style>
    .browse-root { font-family: 'Outfit', sans-serif; }
    .filter-sidebar { background:#fff; border:1px solid #ede8df; border-radius:4px; padding:1.75rem; position:sticky; top:5.5rem; }
    .filter-title { font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:600; color:#0f0f0f; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; }
    .filter-reset { font-size:0.7rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#c9a96e; text-decoration:none; }
    .filter-reset:hover { color:#9a7340; }
    .filter-group { margin-bottom:1.375rem; }
    .filter-label { font-size:0.65rem; letter-spacing:0.12em; text-transform:uppercase; color:#8c8070; font-weight:600; display:block; margin-bottom:0.625rem; }
    .filter-input, .filter-select { font-family:'Outfit',sans-serif; font-size:0.8125rem; color:#0f0f0f; background:#faf7f2; border:1px solid #ddd5c8; border-radius:3px; padding:0.625rem 0.875rem; width:100%; transition:border-color 0.2s ease; }
    .filter-input:focus, .filter-select:focus { outline:none; border-color:#c9a96e; box-shadow:0 0 0 3px rgba(201,169,110,0.1); background:#fff; }
    .filter-select { appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2rem; cursor:pointer; }
    .check-item { display:flex; align-items:center; gap:0.5rem; cursor:pointer; padding:0.25rem 0; }
    .check-item input[type="checkbox"] { width:14px; height:14px; accent-color:#c9a96e; cursor:pointer; }
    .check-item-label { font-size:0.8125rem; color:#4a4038; }
    .filter-sep { height:1px; background:#f0ece4; margin:1.375rem 0; }
    .filter-submit { width:100%; padding:0.75rem; background:#c9a96e; color:#0f0f0f; font-family:'Outfit',sans-serif; font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; border:none; border-radius:3px; cursor:pointer; transition:background 0.2s ease; }
    .filter-submit:hover { background:#b5924f; }
    .filter-chip { display:inline-flex; align-items:center; gap:0.4rem; padding:0.25rem 0.625rem; background:rgba(201,169,110,0.1); border:1px solid rgba(201,169,110,0.3); border-radius:2px; font-size:0.72rem; font-weight:500; color:#6b5e52; }
    .filter-chip a { color:#9a7340; text-decoration:none; }
    .filter-chip a:hover { color:#c0392b; }
    .sort-dropdown { font-family:'Outfit',sans-serif; font-size:0.78rem; font-weight:500; color:#3a3028; background:#fff; border:1px solid #ddd5c8; border-radius:3px; padding:0.45rem 2rem 0.45rem 0.75rem; appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.625rem center; cursor:pointer; }
    .sort-dropdown:focus { outline:none; border-color:#c9a96e; }
</style>

<div class="browse-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.375rem;">
                <div style="width:1.5rem; height:1px; background:#c9a96e;"></div>
                <span style="font-size:0.65rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e; font-weight:600;">For Sale</span>
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(1.75rem,3.5vw,2.5rem); font-weight:600; color:#0f0f0f; line-height:1.1;">
                Properties <em style="color:#c9a96e; font-style:italic;">For Sale</em>
            </h1>
            <p style="font-size:0.875rem; font-weight:300; color:#8c8070; margin-top:0.375rem;">
                {{ $properties->total() ?? $properties->count() }} properties available
            </p>
        </div>

        {{-- Active filter chips --}}
        @if(request()->anyFilled(['q','type','category','min_price','max_price','bedrooms','bathrooms','amenities']))
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center;">
                @if(request('q'))
                    <span class="filter-chip">"{{ request('q') }}" <a href="{{ route('buyer.properties.buy', array_merge(request()->except(['q','page']))) }}">×</a></span>
                @endif
                @if(request('type'))
                    <span class="filter-chip">{{ ucfirst(request('type')) }} <a href="{{ route('buyer.properties.buy', request()->except(['type','page'])) }}">×</a></span>
                @endif
                @if(request('min_price') || request('max_price'))
                    <span class="filter-chip">Price range <a href="{{ route('buyer.properties.buy', request()->except(['min_price','max_price','page'])) }}">×</a></span>
                @endif
                @if(request('bedrooms'))
                    <span class="filter-chip">{{ request('bedrooms') }}+ Beds <a href="{{ route('buyer.properties.buy', request()->except(['bedrooms','page'])) }}">×</a></span>
                @endif
                <a href="{{ route('buyer.properties.buy') }}"
                   style="font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#c0392b; text-decoration:none;">Clear all</a>
            </div>
        @endif
    </div>

    <div style="display:flex; gap:2rem; align-items:flex-start;">

        {{-- ── SIDEBAR ── --}}
        <aside style="width:17rem; flex-shrink:0;" class="hidden lg:block">
            <div class="filter-sidebar">
                <div class="filter-title">
                    Filters
                    <a href="{{ route('buyer.properties.buy') }}" class="filter-reset">Reset</a>
                </div>

                <form action="{{ route('buyer.properties.buy') }}" method="GET" id="filter-form">
                    <input type="hidden" name="sort" value="{{ request('sort','relevance') }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page',12) }}">

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

                    {{-- Type --}}
                    <div class="filter-group">
                        <label class="filter-label">Property Type</label>
                        <select name="type" class="filter-select" id="type-select">
                            <option value="">All Types</option>
                            @php $typeLabels = ['flat'=>'Flat/Apartment','house'=>'House','land'=>'Land/Plot','commercial'=>'Commercial','office'=>'Office','warehouse'=>'Warehouse']; @endphp
                            @php
                                $propTypes = \App\Models\Property::approved()->where('purpose','buy')->select('type')->distinct()->pluck('type');
                            @endphp
                            @foreach($propTypes as $t)
                                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $typeLabels[$t] ?? ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-select">
                            <option value="">All Categories</option>
                            @php $cats = \App\Models\Property::approved()->where('purpose','buy')->select('category')->distinct()->pluck('category'); @endphp
                            @foreach($cats as $c)
                                <option value="{{ $c }}" {{ request('category') === $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-sep"></div>

                    {{-- Price --}}
                    <div class="filter-group">
                        <label class="filter-label">Price Range (NPR)</label>
                        @php
                            $minP = \App\Models\Property::approved()->where('purpose','buy')->min('price') ?? 0;
                            $maxP = \App\Models\Property::approved()->where('purpose','buy')->max('price') ?? 100000000;
                        @endphp
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="filter-input" style="width:50%;">
                            <span style="color:#b0a090; flex-shrink:0;">–</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="filter-input" style="width:50%;">
                        </div>
                        <p style="font-size:0.65rem; color:#b0a090; margin-top:0.375rem;">Rs {{ number_format($minP) }} – Rs {{ number_format($maxP) }}</p>
                    </div>

                    {{-- Bedrooms (shown for flat/house) --}}
                    <div class="filter-group" id="bedrooms-container" @class(['hidden' => !in_array(request('type'),['flat','house']) && request('type') !== ''])>
                        <label class="filter-label">Bedrooms</label>
                        <select name="bedrooms" class="filter-select">
                            <option value="">Any</option>
                            @foreach(range(1,5) as $n)
                                <option value="{{ $n }}" {{ request('bedrooms') == $n ? 'selected' : '' }}>{{ $n }}+ Bedroom{{ $n > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group" id="bathrooms-container" @class(['hidden' => !in_array(request('type'),['flat','house']) && request('type') !== ''])>
                        <label class="filter-label">Bathrooms</label>
                        <select name="bathrooms" class="filter-select">
                            <option value="">Any</option>
                            @foreach(range(1,4) as $n)
                                <option value="{{ $n }}" {{ request('bathrooms') == $n ? 'selected' : '' }}>{{ $n }}+ Bathroom{{ $n > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Area --}}
                    <div class="filter-group">
                        <label class="filter-label">Area (sq.ft)</label>
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <input type="number" name="min_area" value="{{ request('min_area') }}" placeholder="Min" class="filter-input" style="width:50%;">
                            <span style="color:#b0a090; flex-shrink:0;">–</span>
                            <input type="number" name="max_area" value="{{ request('max_area') }}" placeholder="Max" class="filter-input" style="width:50%;">
                        </div>
                    </div>

                    <div class="filter-sep"></div>

                    {{-- Amenities --}}
                    <div class="filter-group">
                        <label class="filter-label">Amenities</label>
                        <div style="max-height:12rem; overflow-y:auto; display:flex; flex-direction:column; gap:0.125rem;">
                            @php
                                $amenities = ['parking'=>'Parking','water'=>'Water Supply','electricity'=>'Electricity','security'=>'Security','garden'=>'Garden','balcony'=>'Balcony','gym'=>'Gym','lift'=>'Lift','ac'=>'AC','internet'=>'Internet'];
                                $selAmenities = (array) request('amenities',[]);
                            @endphp
                            @foreach($amenities as $k => $l)
                                <label class="check-item">
                                    <input type="checkbox" name="amenities[]" value="{{ $k }}" {{ in_array($k, $selAmenities) ? 'checked' : '' }}>
                                    <span class="check-item-label">{{ $l }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="filter-submit">Apply Filters</button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN CONTENT ── --}}
        <main style="flex:1; min-width:0;">

            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1.5rem;">
                <span style="font-size:0.8rem; font-weight:300; color:#8c8070;">{{ $properties->total() }} properties found</span>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                    <select class="sort-dropdown" id="sort-select"
                            onchange="window.location.href = updateQS(window.location.href,'sort',this.value)">
                        <option value="relevance" {{ request('sort','relevance') == 'relevance' ? 'selected' : '' }}>Most Relevant</option>
                        <option value="latest"    {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low→High</option>
                        <option value="price_desc"{{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High→Low</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($properties as $property)
                    <x-property-card :property="$property" />
                @empty
                    <div class="col-span-full" style="text-align:center; padding:5rem 2rem; background:#fff; border:1px solid #ede8df; border-radius:4px;">
                        <p style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.5rem;">No Properties For Sale</p>
                        <p style="font-size:0.875rem; font-weight:300; color:#8c8070; margin-bottom:1.5rem;">Try adjusting your filters.</p>
                        <a href="{{ route('buyer.properties.buy') }}"
                           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.75rem;
                                  background:#c9a96e; color:#0f0f0f; font-size:0.75rem; font-weight:700;
                                  letter-spacing:0.1em; text-transform:uppercase; border-radius:3px; text-decoration:none;">
                            Clear Filters
                        </a>
                    </div>
                @endforelse
            </div>

            @if($properties->hasPages())
                <div style="margin-top:3rem;">{{ $properties->withQueryString()->links() }}</div>
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
document.addEventListener('DOMContentLoaded', function() {
    const ts = document.getElementById('type-select');
    const bc = document.getElementById('bedrooms-container');
    const bac = document.getElementById('bathrooms-container');
    function toggle() {
        const show = ['flat','house'].includes(ts.value) || ts.value === '';
        bc.classList.toggle('hidden', !show);
        bac.classList.toggle('hidden', !show);
    }
    if (ts) ts.addEventListener('change', toggle);
});
</script>
@endpush

</x-app-layout>