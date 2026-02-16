<x-public>

    {{-- Hero Section with Image Gallery --}}
    <div class="relative">
        {{-- Main Image --}}
        <div class="relative h-[60vh] min-h-[500px] w-full bg-gray-900">
            <img src="{{ $property->image_url ?? 'https://via.placeholder.com/1200x800' }}" alt="{{ $property->title }}"
                class="w-full h-full object-cover opacity-90">

            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>

            {{-- Back Button --}}
            <a href="{{ route('properties.list')}}"
                class="absolute top-6 left-6 bg-white/90 backdrop-blur-sm hover:bg-white text-gray-700 rounded-full px-5 py-2.5 text-sm font-medium flex items-center transition-all shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Results
            </a>

            {{-- Property Title Overlay --}}
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 text-white">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-full">
                            {{ ucfirst($property->purpose) }}
                        </span>
                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                            {{ ucfirst($property->type) }}
                        </span>
                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-full">
                            {{ ucfirst($property->category) }}
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-3">{{ $property->title }}</h1>
                    <p class="text-xl text-white/90 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $property->location }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Thumbnail Gallery --}}
        @if($property->gallery ?? false)
            <div
                class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 flex gap-3 p-2 bg-white rounded-2xl shadow-xl">
                @foreach($property->gallery as $image)
                    <button
                        class="w-20 h-20 rounded-lg overflow-hidden ring-2 ring-transparent hover:ring-blue-500 transition-all">
                        <img src="{{ $image }}" alt="Gallery" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-10">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column - Details --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Key Features Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-5 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Key Features
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if($property->bedrooms)
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-3xl mb-2">🛏️</div>
                                <div class="text-sm text-gray-500">Bedrooms</div>
                                <div class="text-xl font-semibold text-gray-900">{{ $property->bedrooms }}</div>
                            </div>
                        @endif

                        @if($property->bathrooms)
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-3xl mb-2">🚿</div>
                                <div class="text-sm text-gray-500">Bathrooms</div>
                                <div class="text-xl font-semibold text-gray-900">{{ $property->bathrooms }}</div>
                            </div>
                        @endif

                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <div class="text-3xl mb-2">📐</div>
                            <div class="text-sm text-gray-500">Area</div>
                            <div class="text-xl font-semibold text-gray-900">{{ number_format($property->area) }} <span
                                    class="text-sm">sqft</span></div>
                        </div>

                        @if($property->parking)
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-3xl mb-2">🚗</div>
                                <div class="text-sm text-gray-500">Parking</div>
                                <div class="text-xl font-semibold text-green-600">Available</div>
                            </div>
                        @endif

                        @if($property->water)
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-3xl mb-2">💧</div>
                                <div class="text-sm text-gray-500">Water</div>
                                <div class="text-xl font-semibold text-green-600">Available</div>
                            </div>
                        @endif

                        @if($property->furnished)
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <div class="text-3xl mb-2">🪑</div>
                                <div class="text-sm text-gray-500">Furnished</div>
                                <div class="text-xl font-semibold text-gray-900">{{ ucfirst($property->furnished) }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description Card --}}
                @if($property->description)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            Description
                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-600">
                            {{ nl2br(e($property->description)) }}
                        </div>
                    </div>
                @endif

                {{-- Amenities Card --}}
                @if($property->amenities ?? false)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Amenities
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->amenities as $amenity)
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $amenity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column - Sidebar --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Price Card --}}
                <div
                    class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white sticky top-6">
                    <div class="text-sm text-blue-100 mb-2">Price</div>
                    <div class="text-4xl font-bold mb-4">Rs {{ number_format($property->price) }}</div>
                    @if($property->purpose === 'rent')
                        <div class="text-blue-100 text-sm">Per Month</div>
                    @endif

                    <hr class="border-blue-400 my-6">

                    {{-- Contact Form --}}
                    <h3 class="text-lg font-semibold mb-4">Interested in this property?</h3>

                    <form class="space-y-4">
                        <input type="text" placeholder="Your Name"
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white">

                        <input type="email" placeholder="Email Address"
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white">

                        <input type="tel" placeholder="Phone Number"
                            class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white">

                        <button type="submit"
                            class="w-full bg-white text-blue-600 font-semibold py-3 rounded-xl hover:bg-blue-50 transition-colors shadow-lg">
                            Request Information
                        </button>
                    </form>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-4">
                        <button
                            class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Save
                        </button>
                        <button
                            class="flex-1 flex items-center justify-center gap-2 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition-colors text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share
                        </button>
                    </div>
                </div>

                {{-- Property ID --}}
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-sm text-gray-500 text-center">
                    Property ID: #{{ str_pad($property->id, 6, '0', STR_PAD_LEFT) }}
                </div>

            </div>

        </div>

        {{-- Map Section --}}
        @if($property->latitude && $property->longitude)
            <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.788L9 2m0 18l6-3m-6 3V7m6 13l4.553-2.276A2 2 0 0021 16.382V5.618a2 2 0 00-1.105-1.788L15 2m0 18V7" />
                    </svg>
                    Location
                </h2>
                <div class="aspect-[16/6] bg-gray-200 rounded-xl overflow-hidden">
                    {{-- Add your map component here --}}
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                        Map View - {{ $property->location }}
                    </div>
                </div>
            </div>
        @endif

        {{-- RECOMMENDATIONS --}}
        @if($recommendations->count())
            <div class="mt-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Similar Properties</h2>
                    <a href="{{ route('properties.index', ['type' => $property->type]) }}"
                        class="text-blue-600 hover:text-blue-700 font-medium flex items-center">
                        View All
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendations as $item)
                        <div
                            class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-100 overflow-hidden">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img src="{{ $item->image_url ?? 'https://via.placeholder.com/400x300' }}"
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="p-5">
                                <h3
                                    class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $item->location }}
                                </p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xl font-bold text-blue-600">Rs {{ number_format($item->price) }}</span>
                                    <a href="{{ route('properties.show', $item->id) }}"
                                        class="text-sm bg-blue-50 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-public>