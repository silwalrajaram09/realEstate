<x-app-layout>
    {{-- Breadcrumb --}}
   

    <div class="max-w-7xl mx-auto px-6 py-8">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Browse Properties</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Find your perfect property from {{ $properties->total() ?? ($properties->count() ?? 0) }} available listings</p>
            </div>
            
            {{-- Active Filters Summary --}}
            @php
                $hasActiveFilters = request()->hasAny(['purpose', 'type', 'category', 'q', 'min_price', 'max_price', 'bedrooms']);
            @endphp
            @if($hasActiveFilters)
                <div class="mt-4 md:mt-0 flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Active filters:</span>
                    @if(request('purpose'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            {{ ucfirst(request('purpose')) }}
                            <a href="{{ request()->fullUrlWithQuery(['purpose' => null]) }}" class="hover:text-blue-900 dark:hover:text-blue-200">×</a>
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            {{ ucfirst(request('type')) }}
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="hover:text-blue-900 dark:hover:text-blue-200">×</a>
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            {{ ucfirst(request('category')) }}
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="hover:text-blue-900 dark:hover:text-blue-200">×</a>
                        </span>
                    @endif
                    @if(request('q'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                            "{{ request('q') }}"
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="hover:text-blue-900 dark:hover:text-blue-200">×</a>
                        </span>
                    @endif
                    <a href="{{ route('buyer.properties') }}" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 ml-2">Clear all</a>
                </div>
            @endif
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filters --}}
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h2>
                        @if($hasActiveFilters)
                            <a href="{{ route('buyer.properties') }}" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Reset all</a>
                        @endif
                    </div>

                    <form action="{{ route('buyer.properties') }}" method="GET" class="space-y-6">
                        {{-- Search --}}
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="q" 
                                    id="q"
                                    value="{{ request('q') }}" 
                                    placeholder="Title, location..." 
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                >
                                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Purpose --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Purpose</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['buy' => 'Buy', 'sell' => 'Sell', 'rent' => 'Rent'] as $value => $label)
                                    <label class="cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="purpose" 
                                            value="{{ $value }}"
                                            {{ request('purpose') === $value ? 'checked' : '' }}
                                            class="sr-only peer"
                                        >
                                        <span class="block text-center py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-700 transition">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Property Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Property Type</label>
                            <select name="type" class="w-full py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Types</option>
                                <option value="flat" {{ request('type') === 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>House</option>
                                <option value="land" {{ request('type') === 'land' ? 'selected' : '' }}>Land</option>
                            </select>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select name="category" class="w-full py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Categories</option>
                                <option value="residential" {{ request('category') === 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ request('category') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ request('category') === 'industrial' ? 'selected' : '' }}>Industrial</option>
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price Range (NPR)</label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    value="{{ request('min_price') }}" 
                                    placeholder="Min" 
                                    class="w-full py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                >
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    value="{{ request('max_price') }}" 
                                    placeholder="Max" 
                                    class="w-full py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                >
                            </div>
                        </div>

{{-- Location Coordinates (Hidden - for GPS-based search) --}}
                        <input type="hidden" name="lat" id="lat" value="{{ request('lat') }}">
                        <input type="hidden" name="lng" id="lng" value="{{ request('lng') }}">

                        {{-- Location Search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                            <button 
                                type="button" 
                                onclick="getCurrentLocation()"
                                class="w-full flex items-center justify-center gap-2 py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span id="location-text">Use my current location</span>
                            </button>
                            @if(request('lat') && request('lng'))
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    ✓ Location set ({{ number_format(request('lat'), 4) }}, {{ number_format(request('lng'), 4) }})
                                    <a href="{{ route('buyer.properties') }}" class="ml-2 text-red-500 hover:text-red-700">(clear)</a>
                                </p>
                            @endif
                        </div>

                        {{-- HOW LOCATION-BASED SEARCH WORKS:
                             1. User clicks "Use my current location" → Browser GPS captures coordinates
                             2. JavaScript fills hidden inputs: lat=27.7172, lng=85.3240
                             3. User clicks "Apply Filters" → Form submits with all filters INCLUDING lat/lng
                             4. Controller receives request → calls PropertyRecommendationService->search()
                             5. Service calculates distance using Haversine formula for each property
                             6. Distance score added to relevance_score (properties within 50km get points)
                             7. Results sorted by total relevance_score (nearest properties ranked higher)
                        --}}

                        @push('scripts')
                        <script>
                            function getCurrentLocation() {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(
                                        function(position) {
                                            document.getElementById('lat').value = position.coords.latitude.toFixed(6);
                                            document.getElementById('lng').value = position.coords.longitude.toFixed(6);
                                            document.getElementById('location-text').textContent = 'Location set!';
                                            document.getElementById('location-text').classList.add('text-green-600');
                                        },
                                        function(error) {
                                            alert('Unable to get location: ' + error.message);
                                        }
                                    );
                                } else {
                                    alert('Geolocation is not supported by your browser.');
                                }
                            }
                        </script>
                        @endpush

                        {{-- Bedrooms --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bedrooms</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['' => 'Any', '1' => '1+', '2' => '2+', '3' => '3+', '4' => '4+', '5' => '5+'] as $value => $label)
                                    <label class="cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="bedrooms" 
                                            value="{{ $value }}"
                                            {{ request('bedrooms') === $value ? 'checked' : '' }}
                                            class="sr-only peer"
                                        >
                                        <span class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-700 transition">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bathrooms --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bathrooms</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['' => 'Any', '1' => '1+', '2' => '2+', '3' => '3+'] as $value => $label)
                                    <label class="cursor-pointer">
                                        <input 
                                            type="radio" 
                                            name="bathrooms" 
                                            value="{{ $value }}"
                                            {{ request('bathrooms') === $value ? 'checked' : '' }}
                                            class="sr-only peer"
                                        >
                                        <span class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-700 transition">
                                            {{ $label }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Quick Price Buttons --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quick Price</label>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => 5000000]) }}" 
                                   class="px-3 py-1.5 text-xs font-medium border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition {{ request('max_price') == 5000000 && !request('min_price') ? 'bg-blue-100 dark:bg-blue-900/50 border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400' }}">
                                    Under 50L
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => 5000000, 'max_price' => 10000000]) }}" 
                                   class="px-3 py-1.5 text-xs font-medium border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition {{ request('min_price') == 5000000 && request('max_price') == 10000000 ? 'bg-blue-100 dark:bg-blue-900/50 border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400' }}">
                                    50L - 1Cr
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['min_price' => 10000000, 'max_price' => null]) }}" 
                                   class="px-3 py-1.5 text-xs font-medium border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:border-blue-300 dark:hover:border-blue-700 transition {{ request('min_price') == 10000000 && !request('max_price') ? 'bg-blue-100 dark:bg-blue-900/50 border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400' }}">
                                    1Cr+
                                </a>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Main Content - Property Listings --}}
            <main class="flex-1">
                {{-- Results Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600 dark:text-gray-400">{{ $properties->total() }} properties found</span>
                        {{-- Sort Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                Sort by: 
                                @php
                                    $sortLabels = [
                                        'latest' => 'Latest',
                                        'price_asc' => 'Price: Low to High',
                                        'price_desc' => 'Price: High to Low',
                                    ];
                                    $currentSort = request('sort', 'latest');
                                @endphp
                                <span class="font-medium dark:text-gray-300">{{ $sortLabels[$currentSort] ?? 'Latest' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-10">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ $currentSort === 'latest' ? 'text-blue-600 font-medium dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">Latest</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ $currentSort === 'price_asc' ? 'text-blue-600 font-medium dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">Price: Low to High</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 {{ $currentSort === 'price_desc' ? 'text-blue-600 font-medium dark:text-blue-400' : 'text-gray-700 dark:text-gray-300' }}">Price: High to Low</a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- View Toggle --}}
                    <div class="flex items-center gap-2">
                        <button class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-400" title="Grid View">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg text-gray-400 dark:text-gray-500" title="List View">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Properties Grid --}}
                
 <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="properties-grid">
                    @forelse($properties as $property)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group property-card"
                            data-property-id="{{ $property->id }}">
                            {{-- Image Section --}}
                            <div class="relative h-52 bg-gray-200 overflow-hidden">
                                <img src="{{ $property->image_url }}" alt="{{ $property->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                {{-- Badges --}}
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-600 text-white">For Sale</span>
                                    @if($property->type)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm rounded-full text-gray-700 shadow-sm">{{ $property->type_label }}</span>
                                    @endif
                                    @if($property->created_at->diffInDays(now()) < 7)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">New</span>
                                    @endif
                                </div>

                                {{-- Price Badge --}}
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-md">
                                    <span class="text-sm font-bold text-gray-900">{{ $property->formatted_price }}</span>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition line-clamp-1">
                                    {{ $property->title }}
                                </h3>

                                <div class="flex items-center gap-1 text-gray-500 mt-2">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="text-sm line-clamp-1">{{ $property->location }}</span>
                                </div>

                                <div class="flex items-center gap-4 mt-4 text-gray-600">
                                    @if($property->bedrooms)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                            <span class="text-sm">{{ $property->bedrooms }} Bed</span>
                                        </div>
                                    @endif
                                    @if($property->bathrooms)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                            </svg>
                                            <span class="text-sm">{{ $property->bathrooms }} Bath</span>
                                        </div>
                                    @endif
                                    @if($property->area)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                            </svg>
                                            <span class="text-sm">{{ $property->area }} sq.ft</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Features Icons --}}
                                @if($property->features)
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        @foreach(array_slice($property->features, 0, 3) as $feature)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($property->features) > 3)
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                                +{{ count($property->features) - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <a href="{{ route('buyer.properties.show', $property->id) }}"
                                    class="mt-4 block w-full text-center py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
        


                @empty
                    {{-- Empty State --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center col-span-full">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No properties found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">We couldn't find any properties matching your criteria. Try adjusting your filters or search terms.</p>
                        <a href="{{ route('buyer.properties') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Clear all filters
                        </a>
                    </div>
                @endforelse
                </div>

                {{-- Pagination --}}
                @if($properties->hasPages())
                    <div class="mt-10">
                        {{ $properties->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>

@php
    function activeClass($condition)
    {
        return $condition ? 'text-blue-600 font-semibold border-b-2 border-blue-600' : 'text-gray-700';
    }
@endphp

