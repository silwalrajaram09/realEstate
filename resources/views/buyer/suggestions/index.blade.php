<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg mb-8 p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Property Suggestions</h1>
                <p class="text-blue-100">Curated properties based on your preferences and search history.</p>
            </div>

            @if(isset($properties) && $properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                            {{-- Image --}}
                            <div class="relative h-48 bg-gray-200">
                                @if($property->image)
                                    <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-600 text-white">
                                        {{ ucfirst($property->purpose) }}
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1 text-xs font-medium bg-white/90 rounded-full text-gray-700">
                                        {{ ucfirst($property->type) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-5">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                                <div class="flex items-center gap-1 text-gray-500 mt-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span class="text-sm">{{ $property->location }}</span>
                                </div>

                                <div class="flex items-center gap-4 mt-3 text-gray-600 text-sm">
                                    @if($property->bedrooms)
                                        <span>{{ $property->bedrooms }} Bed</span>
                                    @endif
                                    @if($property->bathrooms)
                                        <span>{{ $property->bathrooms }} Bath</span>
                                    @endif
                                    <span>{{ $property->area }} sq.ft</span>
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-xl font-bold text-blue-600">Rs
                                        {{ number_format($property->price) }}</span>
                                    <a href="{{ route('buyer.properties.show', $property->id) }}"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($properties->hasPages())
                    <div class="mt-8">
                        {{ $properties->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Suggestions Yet</h3>
                    <p class="text-gray-500 mb-6">Start browsing properties to get personalized suggestions based on your
                        preferences.</p>
                    <a href="{{ route('buyer.properties') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        Browse Properties
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>