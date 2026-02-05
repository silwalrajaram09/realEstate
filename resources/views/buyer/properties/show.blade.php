<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Property Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <!-- Image Gallery -->
                        <div class="relative h-96 bg-gray-200">
                            @if($property->image)
                                <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <span class="px-4 py-2 text-sm font-semibold rounded-full
                                    @if($property->purpose === 'buy') bg-green-600 text-white
                                    @else bg-blue-600 text-white @endif">
                                    For {{ ucfirst($property->purpose) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-8">
                            <!-- Title & Location -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900">{{ $property->title }}</h1>
                                    <div class="flex items-center gap-2 text-gray-500 mt-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span>{{ $property->location }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div class="flex flex-wrap gap-3 mb-6">
                                <span class="px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-full">
                                    {{ ucfirst($property->type) }}
                                </span>
                                <span class="px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-full">
                                    {{ ucfirst($property->category) }}
                                </span>
                                @if($property->status === 'approved')
                                    <span class="px-4 py-2 text-sm font-medium bg-green-100 text-green-700 rounded-full">
                                        Verified
                                    </span>
                                @endif
                            </div>

                            <!-- Key Features -->
                            <div class="grid grid-cols-4 gap-4 mb-8">
                                @if($property->bedrooms)
                                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                                        <svg class="w-8 h-8 mx-auto text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                        <p class="text-2xl font-bold text-gray-900">{{ $property->bedrooms }}</p>
                                        <p class="text-sm text-gray-500">Bedrooms</p>
                                    </div>
                                @endif
                                @if($property->bathrooms)
                                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                                        <svg class="w-8 h-8 mx-auto text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                        </svg>
                                        <p class="text-2xl font-bold text-gray-900">{{ $property->bathrooms }}</p>
                                        <p class="text-sm text-gray-500">Bathrooms</p>
                                    </div>
                                @endif
                                <div class="bg-gray-50 p-4 rounded-xl text-center">
                                    <svg class="w-8 h-8 mx-auto text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    <p class="text-2xl font-bold text-gray-900">{{ $property->area }}</p>
                                    <p class="text-sm text-gray-500">Sq.ft</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-xl text-center">
                                    <svg class="w-8 h-8 mx-auto text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-2xl font-bold text-gray-900">{{ $property->created_at->diffForHumans() }}</p>
                                    <p class="text-sm text-gray-500">Listed</p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($property->description)
                                <div class="mb-8">
                                    <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                                    <p class="text-gray-600 leading-relaxed">{{ $property->description }}</p>
                                </div>
                            @endif

                            <!-- Amenities -->
                            <div class="border-t pt-8">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Amenities</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $property->parking ? 'bg-green-50' : 'bg-gray-50' }}">
                                        @if($property->parking)
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        @endif
                                        <span class="{{ $property->parking ? 'text-green-700' : 'text-gray-500' }}">Parking</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 rounded-lg {{ $property->water ? 'bg-green-50' : 'bg-gray-50' }}">
                                        @if($property->water)
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        @endif
                                        <span class="{{ $property->water ? 'text-green-700' : 'text-gray-500' }}">Water Supply</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Price Card -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6 sticky top-6">
                        <div class="p-6">
                            <p class="text-sm text-gray-500 mb-1">Price</p>
                            <p class="text-4xl font-bold text-blue-600 mb-4">Rs {{ number_format($property->price) }}</p>

                            <button class="w-full py-4 bg-blue-600 text-white rounded-xl font-semibold text-lg hover:bg-blue-700 transition mb-3">
                                Contact Seller
                            </button>
                            <button class="w-full py-4 border-2 border-blue-600 text-blue-600 rounded-xl font-semibold text-lg hover:bg-blue-50 transition">
                                Save to Favorites
                            </button>
                        </div>
                    </div>

                    <!-- Seller Info -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Seller Information</h3>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xl font-bold text-blue-600">
                                        {{ strtoupper(substr($property->seller->name ?? 'NA', 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $property->seller->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">Property Owner</p>
                                </div>
                            </div>
                            <button class="w-full py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Call Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            @if(isset($recommendations) && $recommendations->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Properties</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendations as $rec)
                            <a href="{{ route('buyer.properties.show', $rec->id) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                                <div class="relative h-48 bg-gray-200">
                                    @if($rec->image)
                                        <img src="{{ asset('images/' . $rec->image) }}" alt="{{ $rec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full bg-white/90 text-gray-700">
                                        {{ ucfirst($rec->type) }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">{{ $rec->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">{{ $rec->location }}</p>
                                    <p class="text-lg font-bold text-blue-600 mt-2">Rs {{ number_format($rec->price) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

