<x-app-layout>
    @php
        $hasImage = !empty($property->image);
        $isBuy = $property->purpose === 'buy';
    @endphp

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        
        {{-- Breadcrumb Navigation --}}
        <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex items-center text-sm text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 7-7 7 7M5 10v10h14V10" />
                        </svg>
                        Dashboard
                    </a>
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('buyer.properties') }}" class="hover:text-blue-600 transition">Properties</a>
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-700 font-medium truncate max-w-xs">{{ $property->title }}</span>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

                <!-- ================= MAIN CONTENT ================= -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Main Property Card -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

                        <!-- Image Gallery Section with Modern Carousel -->
                        <div class="relative">
                            <!-- Main Image with Zoom Effect -->
                            <div class="relative h-[500px] bg-gray-100 group cursor-pointer" onclick="openLightbox()">
                                @if($property->image_url)
                                    <img src="{{ $property->image_url }}" 
                                         alt="{{ $property->title }}"
                                         class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                                         loading="eager"
                                         id="mainPropertyImage">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-4 text-gray-400">No image available</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Zoom Indicator -->
                                <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1.5 rounded-lg text-sm backdrop-blur-sm opacity-0 group-hover:opacity-100 transition">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                    Click to zoom
                                </div>

                                <!-- Purpose Badge -->
                                <div class="absolute top-6 left-6 flex gap-3">
                                    <span class="px-5 py-2.5 text-sm font-semibold rounded-full shadow-lg backdrop-blur-sm
                                        {{ $isBuy ? 'bg-emerald-500' : 'bg-blue-500' }} text-white">
                                        For {{ ucfirst($property->purpose) }}
                                    </span>
                                    
                                    @if($property->status === 'approved')
                                        <span class="px-5 py-2.5 bg-white/90 backdrop-blur-sm text-emerald-600 rounded-full text-sm font-semibold shadow-lg flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    @endif
                                </div>

                                <!-- Image Counter -->
                                @if($property->gallery ?? false)
                                    <div class="absolute top-6 right-6 bg-black/50 text-white px-3 py-1.5 rounded-lg text-sm backdrop-blur-sm">
                                        <span id="currentImageIndex">1</span>/{{ count($property->gallery) + 1 }}
                                    </div>
                                @endif
                            </div>

                            <!-- Thumbnail Gallery with Scroll -->
                            @if($property->gallery ?? false)
                                <div class="border-t border-gray-100 p-4">
                                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300">
                                        <button onclick="changeImage(0)" class="thumbnail-btn {{ !$property->image_url ? 'ring-2 ring-blue-500' : '' }}">
                                            <img src="{{ $property->image_url ?? 'https://via.placeholder.com/100x80' }}" 
                                                 alt="Main" 
                                                 class="w-24 h-20 object-cover rounded-lg">
                                        </button>
                                        @foreach($property->gallery as $index => $image)
                                            <button onclick="changeImage({{ $index + 1 }})" class="thumbnail-btn flex-shrink-0">
                                                <img src="{{ $image }}" alt="Gallery {{ $index + 1 }}" 
                                                     class="w-24 h-20 object-cover rounded-lg hover:opacity-90 transition">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-8">

                            <!-- Title and Location with Share Options -->
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-8">
                                <div>
                                    <h1 class="text-4xl font-bold text-gray-900 mb-3 leading-tight">
                                        {{ $property->title }}
                                    </h1>
                                    <div class="flex items-center text-gray-500">
                                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-lg">{{ $property->location }}</span>
                                    </div>
                                </div>
                                
                                <!-- Share Options -->
                                <div class="flex gap-2 mt-4 md:mt-0">
                                    <button class="p-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition" onclick="shareOnFacebook()">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                        </svg>
                                    </button>
                                    <button class="p-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition" onclick="shareOnTwitter()">
                                        <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                                        </svg>
                                    </button>
                                    <button class="p-3 bg-gray-100 hover:bg-gray-200 rounded-xl transition" onclick="copyToClipboard()">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Property Type Badges with Icons -->
                            <div class="flex flex-wrap gap-3 mb-8">
                                <span class="px-5 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 rounded-xl text-sm font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ ucfirst($property->type) }}
                                </span>
                                <span class="px-5 py-2.5 bg-gradient-to-r from-purple-50 to-pink-50 text-purple-700 rounded-xl text-sm font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                    {{ ucfirst($property->category) }}
                                </span>
                                <span class="px-5 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-sm font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Listed {{ $property->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Key Features Grid with Animations -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                                @if($property->bedrooms)
                                    <div class="feature-card group">
                                        <div class="text-4xl mb-3 transform group-hover:scale-110 transition">🛏️</div>
                                        <div class="feature-value">{{ $property->bedrooms }}</div>
                                        <div class="feature-label">Bedrooms</div>
                                    </div>
                                @endif

                                @if($property->bathrooms)
                                    <div class="feature-card group">
                                        <div class="text-4xl mb-3 transform group-hover:scale-110 transition">🚿</div>
                                        <div class="feature-value">{{ $property->bathrooms }}</div>
                                        <div class="feature-label">Bathrooms</div>
                                    </div>
                                @endif

                                <div class="feature-card group">
                                    <div class="text-4xl mb-3 transform group-hover:scale-110 transition">📐</div>
                                    <div class="feature-value">{{ number_format($property->area) }}</div>
                                    <div class="feature-label">Sq. Ft.</div>
                                </div>

                                <div class="feature-card group">
                                    <div class="text-4xl mb-3 transform group-hover:scale-110 transition">📅</div>
                                    <div class="feature-value text-base">{{ $property->created_at->format('M Y') }}</div>
                                    <div class="feature-label">Listed Date</div>
                                </div>
                            </div>

                            <!-- Description with Read More -->
                            @if($property->description)
                                <div class="mb-10">
                                    <h2 class="text-2xl font-bold mb-4 flex items-center">
                                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                        About This Property
                                    </h2>
                                    <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl" id="propertyDescription">
                                        {{ Str::limit($property->description, 300) }}
                                        @if(strlen($property->description) > 300)
                                            <span id="descriptionDots">...</span>
                                            <span id="moreDescription" class="hidden">{{ substr($property->description, 300) }}</span>
                                            <button onclick="toggleDescription()" class="block mt-3 text-blue-600 hover:text-blue-700 font-medium">
                                                Read More
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Amenities & Features with Progress Bars -->
                            <div class="border-t border-gray-200 pt-8 mb-8">
                                <h2 class="text-2xl font-bold mb-6 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Amenities & Features
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @php
                                        $amenities = [
                                            'Parking' => ['available' => $property->parking ?? false, 'icon' => '🚗'],
                                            'Water Supply' => ['available' => $property->water ?? false, 'icon' => '💧'],
                                            'Electricity' => ['available' => true, 'icon' => '⚡'],
                                            'Security' => ['available' => $property->security ?? false, 'icon' => '🔒'],
                                            'Internet' => ['available' => $property->internet ?? false, 'icon' => '🌐'],
                                            'Furnished' => ['available' => $property->furnished ?? false, 'icon' => '🪑'],
                                            'Gym' => ['available' => $property->gym ?? false, 'icon' => '💪'],
                                            'Swimming Pool' => ['available' => $property->pool ?? false, 'icon' => '🏊'],
                                        ];
                                    @endphp

                                    @foreach($amenities as $label => $data)
                                        <div class="flex items-center justify-between p-4 rounded-xl {{ $data['available'] ? 'bg-green-50' : 'bg-gray-50' }}">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xl">{{ $data['icon'] }}</span>
                                                <span class="font-medium {{ $data['available'] ? 'text-gray-900' : 'text-gray-500' }}">
                                                    {{ $label }}
                                                </span>
                                            </div>
                                            @if($data['available'])
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- MAP VIEW SECTION WITH LEAFLET -->
                            <div class="border-t border-gray-200 pt-8">
                                <h2 class="text-2xl font-bold mb-6 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.788L9 2m0 18l6-3m-6 3V7m6 13l4.553-2.276A2 2 0 0021 16.382V5.618a2 2 0 00-1.105-1.788L15 2m0 18V7" />
                                    </svg>
                                    Location on Map
                                </h2>
                                
                                <!-- Leaflet Map Container -->
                                <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">
                                    <div id="propertyMap" class="h-[400px] w-full bg-gray-100 relative z-0">
                                        <!-- Map will be initialized here by Leaflet -->
                                    </div>
                                    
                                    <!-- Map Controls -->
                                    <div class="bg-white p-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <button onclick="zoomToLocation()" class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                </svg>
                                                Zoom to Location
                                            </button>
                                            <button onclick="getDirections()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.788L9 2m0 18l6-3m-6 3V7m6 13l4.553-2.276A2 2 0 0021 16.382V5.618a2 2 0 00-1.105-1.788L15 2m0 18V7" />
                                                </svg>
                                                Get Directions
                                            </button>
                                        </div>
                                        
                                        <!-- Map Type Toggle -->
                                        <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-lg">
                                            <button onclick="setMapType('street')" class="px-3 py-1.5 text-sm rounded-md bg-white shadow-sm">Street</button>
                                            <button onclick="setMapType('satellite')" class="px-3 py-1.5 text-sm rounded-md hover:bg-white transition">Satellite</button>
                                            <button onclick="setMapType('terrain')" class="px-3 py-1.5 text-sm rounded-md hover:bg-white transition">Terrain</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Details -->
                                <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span><span class="font-medium text-gray-900">Latitude:</span> {{ $property->latitude ?? '31.5204' }}° N</span>
                                        <span class="mx-2 hidden sm:inline">|</span>
                                        <span class="block sm:inline mt-1 sm:mt-0"><span class="font-medium text-gray-900">Longitude:</span> {{ $property->longitude ?? '74.3587' }}° E</span>
                                    </div>
                                    <a href="https://www.openstreetmap.org/directions?from=&to={{ $property->latitude ?? '31.5204' }},{{ $property->longitude ?? '74.3587' }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                        Open in OpenStreetMap
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= SIDEBAR (UPDATED - NO STICKY) ================= -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Price Card - Solid & Static (NO STICKY) -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200">
                        <div class="p-6">
                            <!-- Price Display -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 mb-1">Price</p>
                                <div class="flex items-baseline">
                                    <span class="text-2xl font-semibold text-gray-400">Rs</span>
                                    <span class="text-4xl font-bold text-gray-900 ml-1">{{ number_format($property->price) }}</span>
                                </div>
                                @if($property->purpose === 'rent')
                                    <p class="text-sm text-gray-500 mt-1">per month</p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button class="w-full bg-blue-600 text-white font-semibold py-3.5 px-4 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Contact Seller
                                </button>

                                <div class="grid grid-cols-2 gap-3">
                                    <button class="bg-gray-100 text-gray-700 font-medium py-3.5 px-4 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Save
                                    </button>
                                    
                                    <button class="bg-gray-100 text-gray-700 font-medium py-3.5 px-4 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        Share
                                    </button>
                                </div>

                                <button class="w-full bg-gray-100 text-gray-700 font-medium py-3.5 px-4 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Schedule Visit
                                </button>
                            </div>

                            <!-- EMI Calculator -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        EMI Calculator
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Loan Amount (80%)</span>
                                            <span class="font-medium text-gray-900">Rs {{ number_format($property->price * 0.8) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Monthly EMI</span>
                                            <span class="font-bold text-green-600">Rs {{ number_format(($property->price * 0.8 * 0.00833) / (1 - pow(1 + 0.00833, -240))) }}</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-2">*10% interest for 20 years</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Property ID -->
                            <div class="mt-4 flex items-center justify-between text-xs">
                                <span class="text-gray-400">Property ID</span>
                                <span class="font-mono font-medium text-gray-600">#{{ str_pad($property->id, 8, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Card - Solid & Static -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Seller Information
                            </h3>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xl font-bold text-blue-600">
                                        {{ strtoupper(substr($property->seller->name ?? 'NA', 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $property->seller->name ?? 'Unknown Seller' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Seller Stats -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-lg font-bold text-gray-900">12</div>
                                    <div class="text-xs text-gray-500">Properties</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-lg font-bold text-gray-900">8</div>
                                    <div class="text-xs text-gray-500">Sold</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 text-center">
                                    <div class="text-lg font-bold text-gray-900">4.8</div>
                                    <div class="text-xs text-gray-500">Rating</div>
                                </div>
                            </div>

                            <!-- Rating Stars -->
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">(24 reviews)</span>
                            </div>

                            <!-- Contact Buttons -->
                            <div class="space-y-2">
                                <button class="w-full bg-green-600 text-white font-medium py-3.5 px-4 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Call Now
                                </button>
                                
                                <button class="w-full bg-gray-100 text-gray-700 font-medium py-3.5 px-4 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Send Message
                                </button>
                            </div>

                            <button class="w-full mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium text-center">
                                View Full Profile →
                            </button>
                        </div>
                    </div>

                    <!-- Safety Tips - Solid Card -->
                    <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                        <h4 class="font-semibold text-blue-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Safety Tips
                        </h4>
                        
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-sm text-blue-800">
                                <span class="w-5 h-5 rounded-full bg-blue-200 text-blue-700 text-xs flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">1</span>
                                <span>Inspect the property in person before making any payment</span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-blue-800">
                                <span class="w-5 h-5 rounded-full bg-blue-200 text-blue-700 text-xs flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">2</span>
                                <span>Verify all ownership documents with legal expert</span>
                            </li>
                            <li class="flex items-start gap-3 text-sm text-blue-800">
                                <span class="w-5 h-5 rounded-full bg-blue-200 text-blue-700 text-xs flex items-center justify-center flex-shrink-0 mt-0.5 font-bold">3</span>
                                <span>Never pay in cash or via untraceable methods</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Recently Viewed - Solid List -->
                    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">Recently Viewed</h4>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700">View All →</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($recentlyViewed ?? [] as $recent)
                                <a href="#" class="flex items-center gap-3 hover:bg-gray-50 p-2 rounded-lg transition-colors">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <img src="{{ $recent->image_url ?? 'https://via.placeholder.com/48' }}" 
                                             alt="" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $recent->title ?? 'Modern Apartment' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $recent->location ?? 'DHA, Lahore' }}</p>
                                        <p class="text-xs font-semibold text-blue-600 mt-0.5">Rs {{ number_format($recent->price ?? 5000000) }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-6">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-400 text-sm">No recently viewed properties</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= RECOMMENDATIONS ================= -->
            @if(isset($recommendations) && $recommendations->count())
                <div class="mt-20">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Similar Properties You Might Like</h2>
                        <a href="{{ route('buyer.properties', ['type' => $property->type]) }}" 
                           class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                            View All
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendations as $rec)
                            <a href="{{ route('buyer.properties.show', $rec->id) }}"
                               class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
                                <div class="relative h-56 overflow-hidden">
                                    @if($rec->image_url)
                                        <img src="{{ $rec->image_url }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                             loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                            {{ ucfirst($rec->purpose) }}
                                        </span>
                                    </div>
                                    @if($rec->status === 'approved')
                                        <div class="absolute top-3 right-3">
                                            <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Verified</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                        {{ $rec->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $rec->location }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="text-2xl font-bold text-blue-600">Rs {{ number_format($rec->price) }}</span>
                                        <span class="text-sm text-gray-500">{{ $rec->area }} sq.ft</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Lightbox Modal for Image Gallery -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center" onclick="closeLightbox(event)">
        <button class="absolute top-6 right-6 text-white hover:text-gray-300" onclick="closeLightbox()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <button class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300" onclick="previousImage()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <img id="lightboxImage" src="" alt="" class="max-h-[90vh] max-w-[90vw] object-contain">
        <button class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300" onclick="nextImage()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- JavaScript for Interactivity -->
    <script>
        // Initialize Leaflet Map
        let map;
        let marker;
        let currentMapType = 'street';

        function initMap() {
            // Default coordinates (Lahore, Pakistan)
            const defaultLat = {{ $property->latitude ?? 31.5204 }};
            const defaultLng = {{ $property->longitude ?? 74.3587 }};
            
            // Create map with OpenStreetMap tiles
            map = L.map('propertyMap').setView([defaultLat, defaultLng], 15);

            // Add tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add marker with custom icon
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: '<div class="marker-pulse"></div><div class="marker-icon">🏠</div>',
                iconSize: [40, 40],
                popupAnchor: [0, -20]
            });

            marker = L.marker([defaultLat, defaultLng], { 
                icon: customIcon,
                draggable: false,
                riseOnHover: true
            }).addTo(map);

            // Add popup with property info
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <h3 style="font-weight: bold; margin: 0 0 5px 0;">{{ $property->title }}</h3>
                    <p style="margin: 0 0 5px 0; color: #666;">{{ $property->location }}</p>
                    <p style="color: #2563eb; font-weight: bold; margin: 5px 0 0 0;">Rs {{ number_format($property->price) }}</p>
                </div>
            `).openPopup();

            // Add scale control
            L.control.scale({ imperial: false, metric: true }).addTo(map);

            // Add fullscreen button
            L.control.fullscreen = L.Control.extend({
                options: { position: 'topright' },
                onAdd: function() {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                    const button = L.DomUtil.create('a', '', container);
                    button.innerHTML = '⛶';
                    button.href = '#';
                    button.style.fontSize = '20px';
                    button.style.width = '34px';
                    button.style.height = '34px';
                    button.style.lineHeight = '34px';
                    button.style.textAlign = 'center';
                    button.style.backgroundColor = 'white';
                    
                    L.DomEvent.on(button, 'click', function(e) {
                        e.preventDefault();
                        toggleFullscreen();
                    });
                    
                    return container;
                }
            });
            
            new L.control.fullscreen().addTo(map);
        }

        // Toggle fullscreen map
        function toggleFullscreen() {
            const mapElement = document.getElementById('propertyMap');
            if (!document.fullscreenElement) {
                mapElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        // Change map type
        function setMapType(type) {
            currentMapType = type;
            
            // Remove existing tile layers
            map.eachLayer((layer) => {
                if (layer instanceof L.TileLayer) {
                    map.removeLayer(layer);
                }
            });

            // Add new tile layer based on type
            let tileLayer;
            switch(type) {
                case 'satellite':
                    tileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles © Esri'
                    });
                    break;
                case 'terrain':
                    tileLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                        attribution: 'Map data © OpenTopoMap contributors'
                    });
                    break;
                default:
                    tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    });
            }
            
            tileLayer.addTo(map);

            // Update button styles
            document.querySelectorAll('.bg-gray-100 button').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm');
            });
            event.target.classList.add('bg-white', 'shadow-sm');
        }

        // Zoom to property location
        function zoomToLocation() {
            const defaultLat = {{ $property->latitude ?? 31.5204 }};
            const defaultLng = {{ $property->longitude ?? 74.3587 }};
            map.setView([defaultLat, defaultLng], 18);
        }

        // Get directions (opens in OpenStreetMap)
        function getDirections() {
            const lat = {{ $property->latitude ?? 31.5204 }};
            const lng = {{ $property->longitude ?? 74.3587 }};
            window.open(`https://www.openstreetmap.org/directions?from=&to=${lat},${lng}`, '_blank');
        }

        // Image gallery functions
        let currentImageIndex = 0;
        const images = [
            '{{ $property->image_url }}',
            @if($property->gallery ?? false)
                @foreach($property->gallery as $image)
                    '{{ $image }}',
                @endforeach
            @endif
        ].filter(img => img);

        function changeImage(index) {
            if (index >= 0 && index < images.length) {
                currentImageIndex = index;
                document.getElementById('mainPropertyImage').src = images[index];
                document.getElementById('currentImageIndex').textContent = index + 1;
                
                // Update thumbnail highlights
                document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
                    if (i === index) {
                        btn.classList.add('ring-2', 'ring-blue-500');
                    } else {
                        btn.classList.remove('ring-2', 'ring-blue-500');
                    }
                });
            }
        }

        function openLightbox() {
            document.getElementById('lightboxImage').src = images[currentImageIndex];
            document.getElementById('lightbox').classList.remove('hidden');
            document.getElementById('lightbox').classList.add('flex');
        }

        function closeLightbox(event) {
            if (!event || event.target === document.getElementById('lightbox')) {
                document.getElementById('lightbox').classList.add('hidden');
                document.getElementById('lightbox').classList.remove('flex');
            }
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % images.length;
            document.getElementById('lightboxImage').src = images[currentImageIndex];
        }

        function previousImage() {
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            document.getElementById('lightboxImage').src = images[currentImageIndex];
        }

        // Description toggle
        function toggleDescription() {
            const dots = document.getElementById('descriptionDots');
            const moreText = document.getElementById('moreDescription');
            const btnText = event.target;

            if (dots.classList.contains('hidden')) {
                dots.classList.remove('hidden');
                moreText.classList.add('hidden');
                btnText.textContent = 'Read More';
            } else {
                dots.classList.add('hidden');
                moreText.classList.remove('hidden');
                btnText.textContent = 'Read Less';
            }
        }

        // Share functions
        function shareOnFacebook() {
            window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank');
        }

        function shareOnTwitter() {
            window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text={{ $property->title }}', '_blank');
        }

        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            });
        }

        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', initMap);

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').classList.contains('flex')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'ArrowLeft') previousImage();
            }
        });
    </script>

    <!-- Custom CSS for Leaflet Marker -->
    <style>
        .feature-card {
            @apply bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-5 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-lg;
        }
        .feature-value {
            @apply text-2xl font-bold text-gray-900;
        }
        .feature-label {
            @apply text-sm text-gray-500 mt-1;
        }
        .primary-btn {
            @apply w-full py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-blue-800 transition-all transform hover:scale-[1.02] shadow-lg;
        }
        .secondary-btn {
            @apply w-full py-4 border-2 border-blue-600 text-blue-600 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all;
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.9; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .thumbnail-btn {
            @apply flex-shrink-0 transition-all duration-200 hover:opacity-90;
        }
        .scrollbar-thin::-webkit-scrollbar {
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Custom marker styles */
        .custom-marker {
            position: relative;
        }
        .marker-pulse {
            width: 40px;
            height: 40px;
            background: rgba(37, 99, 235, 0.3);
            border-radius: 50%;
            position: absolute;
            animation: pulse 2s infinite;
        }
        .marker-icon {
            width: 30px;
            height: 30px;
            background: #2563eb;
            border-radius: 50% 50% 50% 0;
            position: absolute;
            top: 5px;
            left: 5px;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .marker-icon::after {
            content: '';
            width: 30px;
            height: 30px;
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }
        @keyframes pulse {
            0% {
                transform: scale(0.5);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        /* Leaflet popup customization */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 5px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .leaflet-popup-tip {
            background: white;
        }
        
        /* Fullscreen map styles */
        #propertyMap:-webkit-full-screen {
            width: 100vw;
            height: 100vh;
        }
        #propertyMap:-moz-full-screen {
            width: 100vw;
            height: 100vh;
        }
        #propertyMap:fullscreen {
            width: 100vw;
            height: 100vh;
        }
    </style>
</x-app-layout>