<x-public>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header with Filters Toggle --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10">
            <div>
                <h1 class="text-3xl font-light text-gray-900 tracking-tight">
                    Property<span class="font-semibold text-blue-600">Results</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1.5">
                    Discover your perfect property from our curated selection
                </p>
            </div>

            <div class="flex items-center gap-4 mt-4 sm:mt-0">
                {{-- Sort Dropdown --}}
                <select
                    class="text-sm border-0 bg-gray-50 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500/20 cursor-pointer">
                    <option>Most Recent</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Largest Area</option>
                </select>

                {{-- Results Count --}}
                <span
                    class="inline-flex items-center px-4 py-2.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ number_format($properties->total() ?? $properties->count()) }} properties
                </span>
            </div>
        </div>

        @if($properties->count())

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 auto-rows-fr">

                @foreach($properties as $property)

                    <article
                        class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full border border-gray-100 hover:border-transparent">

                        {{-- IMAGE SECTION with Aspect Ratio --}}
                        <div class="relative aspect-[4/3] rounded-t-2xl overflow-hidden">

                            {{-- Property Image --}}
                            <img src="{{ $property->image_url }}" alt="{{ $property->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-out"
                                loading="lazy">

                            {{-- Overlay Gradient --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            {{-- Badges Container --}}
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                {{-- Purpose Badge --}}
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium uppercase tracking-wider
                                            {{ $property->purpose === 'sale' ? 'bg-emerald-500' : 'bg-blue-500' }} text-white shadow-lg backdrop-blur-sm bg-opacity-90">
                                    {{ $property->purpose === 'sale' ? 'For Sale' : 'For Rent' }}
                                </span>

                                {{-- Type Badge --}}
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white/90 text-gray-700 backdrop-blur-sm shadow-lg">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </div>

                            {{-- Price --}}
                            <div class="absolute bottom-4 right-4">
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-white/95 backdrop-blur-sm text-gray-900 font-semibold text-lg shadow-xl">
                                    Rs {{ number_format($property->price) }}
                                    @if($property->purpose === 'rent')
                                        <span class="text-xs text-gray-500 ml-1 font-normal">/month</span>
                                    @endif
                                </span>
                            </div>

                            {{-- Favorite Button --}}
                            <button
                                class="absolute top-4 right-4 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white transition-all shadow-lg opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>

                        </div>

                        {{-- CONTENT SECTION --}}
                        <div class="p-6 flex-1 flex flex-col">

                            {{-- Title & Location --}}
                            <div class="mb-4">
                                <h3
                                    class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    <a href="{{ route('properties.show', $property->id) }}" class="hover:underline">
                                        {{ $property->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-gray-500 text-sm mt-1.5">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="line-clamp-1">{{ $property->location }}</span>
                                </div>
                            </div>

                            {{-- Property Features Grid --}}
                            <div class="grid grid-cols-3 gap-3 py-4 border-y border-gray-100 mb-4">

                                @if($property->bedrooms)
                                    <div class="text-center">
                                        <div class="text-xl mb-1">🛏️</div>
                                        <div class="text-xs font-medium text-gray-500">Bedrooms</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $property->bedrooms }}</div>
                                    </div>
                                @endif

                                @if($property->bathrooms)
                                    <div class="text-center">
                                        <div class="text-xl mb-1">🚿</div>
                                        <div class="text-xs font-medium text-gray-500">Bathrooms</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $property->bathrooms }}</div>
                                    </div>
                                @endif

                                @if($property->area)
                                    <div class="text-center">
                                        <div class="text-xl mb-1">📏</div>
                                        <div class="text-xs font-medium text-gray-500">Area</div>
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($property->area) }} sqft
                                        </div>
                                    </div>
                                @endif

                            </div>

                            {{-- Additional Features --}}
                            @if($property->features && count($property->features) > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($property->features, 0, 3) as $feature)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 text-xs text-gray-600">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                    @if(count($property->features) > 3)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 text-xs text-gray-600">
                                            +{{ count($property->features) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @endif

                            {{-- View Details Button --}}
                            <a href="{{ route('properties.show', $property->id) }}"
                                class="mt-auto inline-flex items-center justify-center px-6 py-3 bg-gray-50 hover:bg-blue-600 text-gray-700 hover:text-white rounded-xl font-medium transition-all duration-200 group/btn">
                                <span>View Details</span>
                                <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                        </div>

                    </article>

                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-16">
                {{ $properties->appends(request()->query())->links() }}
            </div>

        @else

            {{-- Enhanced Empty State --}}
            <div class="text-center py-24 bg-gradient-to-b from-gray-50 to-white rounded-3xl border border-gray-100">

                <div class="text-7xl mb-6 animate-bounce">🏠</div>

                <h3 class="text-2xl font-semibold text-gray-900 mb-3">
                    No properties found
                </h3>

                <p class="text-gray-500 max-w-md mx-auto mb-8">
                    We couldn't find any properties matching your criteria. Try adjusting your filters or explore other
                    options.
                </p>

                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('properties.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filters
                    </a>

                    <button onclick="history.back()"
                        class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                        Go Back
                    </button>
                </div>

            </div>

        @endif

    </div>

</x-public>