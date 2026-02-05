<x-app-layout>
    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-6 py-8">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Properties for Sale</h1>
                <p class="text-gray-500 mt-1">{{ $properties->total() ?? ($properties->count() ?? 0) }} properties
                    available</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Filters --}}
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                        <a href="{{ route('buyer.properties.buy') }}"
                            class="text-sm text-blue-600 hover:text-blue-700">Reset</a>
                    </div>

                    <form action="{{ route('buyer.properties.buy') }}" method="GET" class="space-y-6">
                        {{-- Search --}}
                        <div>
                            <label for="q" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="q" id="q" value="{{ request('q') }}"
                                    placeholder="Title, location..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        {{-- Property Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                            <select name="type"
                                class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                                <option value="">All Types</option>
                                <option value="flat" {{ request('type') === 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>House</option>
                                <option value="land" {{ request('type') === 'land' ? 'selected' : '' }}>Land</option>
                            </select>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category"
                                class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition bg-white">
                                <option value="">All Categories</option>
                                <option value="residential" {{ request('category') === 'residential' ? 'selected' : '' }}>
                                    Residential</option>
                                <option value="commercial" {{ request('category') === 'commercial' ? 'selected' : '' }}>
                                    Commercial</option>
                            </select>
                        </div>

                        {{-- Price Range --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (NPR)</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}"
                                    placeholder="Min"
                                    class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <span class="text-gray-400">-</span>
                                <input type="number" name="max_price" value="{{ request('max_price') }}"
                                    placeholder="Max"
                                    class="w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Main Content - Property Listings --}}
            <main class="flex-1">
                {{-- Results Header --}}
                <div class="flex items-center justify-between mb-6">
                    <span class="text-gray-600">{{ $properties->total() }} properties found</span>
                </div>

                {{-- Properties Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="properties-grid">
                    @forelse($properties as $property)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group property-card"
                            data-property-id="{{ $property->id }}">
                            {{-- Image Section --}}
                            <div class="relative h-52 bg-gray-200 overflow-hidden">
                                @if($property->image ?? false)
                                    <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-600 text-white">
                                        For Sale
                                    </span>
                                    @if($property->type)
                                        <span
                                            class="px-3 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm rounded-full text-gray-700 shadow-sm">
                                            {{ ucfirst($property->type) }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Price Badge --}}
                                <div
                                    class="absolute top-3 right-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-md">
                                    <span class="text-sm font-bold text-gray-900">Rs
                                        {{ number_format($property->price) }}</span>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="p-5">
                                <h3
                                    class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition line-clamp-1">
                                    {{ $property->title }}
                                </h3>

                                <div class="flex items-center gap-1 text-gray-500 mt-2">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="text-sm line-clamp-1">{{ $property->location }}</span>
                                </div>

                                {{-- Property Features --}}
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

                                {{-- CTA Button --}}
                                <a href="{{ route('buyer.properties.show', $property->id) }}"
                                    class="mt-4 block w-full text-center py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- Empty State --}}
                        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties for sale found</h3>
                            <p class="text-gray-500 mb-6">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('buyer.properties.buy') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
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