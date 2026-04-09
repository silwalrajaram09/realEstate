<div class="flex flex-col lg:flex-row gap-8 align-items-start">
    <style>
        .pill-span.active {
            background: #c9a96e !important;
            border-color: #c9a96e !important;
            color: #0f0f0f !important;
            font-weight: 600 !important;
        }
    </style>
    {{-- ── SIDEBAR ── --}}
    <aside style="width:17rem; flex-shrink:0;" class="hidden lg:block">
        <div class="filter-sidebar">
            <div class="filter-title">
                Filters
                @if($hasFilters)
                    <button wire:click="clearFilters" class="filter-reset">Reset</button>
                @endif
            </div>

            {{-- Loading Indicator --}}
            <div wire:loading.flex style="position:absolute; top:1rem; right:1rem; align-items:center; gap:0.5rem; font-size:0.7rem; color:#c9a96e;">
                <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            </div>

            {{-- Search --}}
            <div class="filter-group">
                <label class="filter-label">Search</label>
                <div style="position:relative; display:flex; gap:0.5rem;">
                    <div style="position:relative; flex:1;">
                        <input type="text" wire:model.live.debounce.500ms="q" placeholder="Title, location…" class="filter-input" style="padding-left:2.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#b0a090" stroke-width="1.75"
                             style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="filter-sep"></div>

            {{-- Purpose --}}
            <div class="filter-group">
                <label class="filter-label">Purpose</label>
                <div class="pill-group">
                    @foreach(['buy' => 'Buy', 'rent' => 'Rent'] as $v => $l)
                        <button type="button" 
                                wire:click="togglePurpose('{{ $v }}')"
                                class="pill-span {{ $purpose === $v ? 'active' : '' }}"
                                style="border-radius: 2px;">
                            {{ $l }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Type --}}
            <div class="filter-group">
                <label class="filter-label">Property Type</label>
                <select wire:model.live="type" class="filter-select">
                    <option value="">All Types</option>
                    <option value="flat">Flat / Apartment</option>
                    <option value="house">House</option>
                    <option value="land">Land / Plot</option>
                    <option value="commercial">Commercial</option>
                </select>
            </div>

            {{-- Category --}}
            <div class="filter-group">
                <label class="filter-label">Category</label>
                <select wire:model.live="category" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="residential">Residential</option>
                    <option value="commercial">Commercial</option>
                    <option value="industrial">Industrial</option>
                </select>
            </div>

            <div class="filter-sep"></div>

            {{-- Price --}}
            <div class="filter-group">
                <label class="filter-label">Price Range (NPR)</label>
                <div style="display:flex; gap:0.5rem; align-items:center; margin-bottom:0.5rem;">
                    <input type="number" wire:model.blur="min_price" placeholder="Min" class="filter-input" style="width:50%;">
                    <span style="color:#b0a090; flex-shrink:0;">–</span>
                    <input type="number" wire:model.blur="max_price" placeholder="Max" class="filter-input" style="width:50%;">
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:0.375rem; margin-top:0.5rem;">
                    <button type="button" wire:click="setPriceRange(null, 5000000)"
                       class="qp-link {{ $max_price == 5000000 && !$min_price ? 'active' : '' }}">Under 50L</button>
                    <button type="button" wire:click="setPriceRange(5000000, 10000000)"
                       class="qp-link {{ $min_price == 5000000 && $max_price == 10000000 ? 'active' : '' }}">50L–1Cr</button>
                    <button type="button" wire:click="setPriceRange(10000000, null)"
                       class="qp-link {{ $min_price == 10000000 && !$max_price ? 'active' : '' }}">1Cr+</button>
                </div>
            </div>

            {{-- Bedrooms --}}
            <div class="filter-group">
                <label class="filter-label">Bedrooms</label>
                <div class="pill-group">
                    @foreach(['1' => '1+', '2' => '2+', '3' => '3+', '4' => '4+', '5' => '5+'] as $v => $l)
                        <button type="button" 
                                wire:click="toggleBedrooms('{{ $v }}')"
                                class="pill-span {{ $bedrooms === $v ? 'active' : '' }}"
                                style="border-radius: 2px;">
                            {{ $l }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="filter-sep"></div>

            {{-- Location GPS --}}
            <div class="filter-group">
                <label class="filter-label">Location</label>
                <div x-data="{
                    loading: false,
                    getLocation() {
                        this.loading = true;
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                pos => {
                                    $wire.set('lat', pos.coords.latitude.toFixed(6));
                                    $wire.set('lng', pos.coords.longitude.toFixed(6));
                                    this.loading = false;
                                },
                                err => {
                                    alert('Unable to get location: ' + err.message);
                                    this.loading = false;
                                }
                            );
                        }
                    }
                }">
                    <button type="button" @click="getLocation()" class="loc-btn" :style="$wire.lat ? 'border-color:#5a8a5a; background:#f4f9f4;' : ''">
                        <template x-if="loading">
                             <svg class="animate-spin h-3 w-3 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="!loading">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </template>
                        <span x-text="$wire.lat ? 'Location Active' : 'Find Near Me'"></span>
                    </button>

                    @if($lat)
                        <button type="button" wire:click="$set('lat', ''); $set('lng', ''); $set('page', 1);" 
                                style="font-size:0.65rem; color:#c0392b; background:none; border:none; padding:0.4rem 0; cursor:pointer; text-decoration:underline;">
                            Clear Location
                        </button>
                    @endif
                </div>
            </div>

            <div class="filter-sep"></div>

            <button type="button" wire:click="$refresh" class="filter-submit">
                Apply Filters
            </button>
        </div>
    </aside>

    {{-- ── MAIN RESULTS AREA ── --}}
    <main style="flex:1; min-width:0;">

        <div class="results-header">
            <span class="results-count">{{ $properties->total() }} properties found</span>
            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                <select wire:model.live="sort" class="sort-dropdown">
                    <option value="latest">Most Recent</option>
                    <option value="price_asc">Price: Low→High</option>
                    <option value="price_desc">Price: High→Low</option>
                </select>
                <select wire:model.live="per_page" class="per-page-select">
                    <option value="12">12 / page</option>
                    <option value="24">24 / page</option>
                    <option value="36">36 / page</option>
                </select>
            </div>
        </div>

        {{-- Grid --}}
        <div wire:loading.class="opacity-50 transition-opacity duration-200" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($properties as $property)
                <x-property-card :property="$property" />
            @empty
                <div class="col-span-full" style="text-align:center; padding:5rem 2rem; background:#fff; border:1px solid #ede8df; border-radius:4px;">
                    <p style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f;">No Properties Found</p>
                    <p style="font-size:0.875rem; font-weight:300; color:#8c8070; margin-bottom:1.5rem;">Try adjusting your filters.</p>
                    <button wire:click="clearFilters" class="filter-submit" style="width:auto; padding:0.7rem 1.75rem;">Clear All Filters</button>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div style="margin-top:3rem;">
            {{ $properties->links() }}
        </div>

    </main>
</div>
