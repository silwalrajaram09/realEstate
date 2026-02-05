<x-public>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            Property Results
        </h2>

        <span class="text-sm text-gray-500">
            {{ $properties->total() ?? $properties->count() }} properties found
        </span>
    </div>

    @forelse($properties as $property)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($properties as $property)
                <div class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden group">
                    {{-- IMAGE --}}
                    <div class="h-48 bg-gray-200 relative">
                        <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-3 py-1 rounded-full">
                            {{ ucfirst($property->purpose) }}
                        </span>

                        <span
                            class="absolute top-3 right-3 bg-white text-blue-600 font-semibold text-sm px-3 py-1 rounded-full shadow">
                            Rs {{ number_format($property->price) }}
                        </span>
                    </div>

                    {{-- CONTENT --}}
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition">
                            {{ $property->title }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            📍 {{ $property->location }}
                        </p>

                        <div class="flex items-center gap-4 text-sm text-gray-600 mt-3">
                            <span>🏠 {{ ucfirst($property->type) }}</span>

                            @if($property->bedrooms)
                                <span>🛏 {{ $property->bedrooms }}</span>
                            @endif

                            @if($property->bathrooms)
                                <span>🛁 {{ $property->bathrooms }}</span>
                            @endif
                        </div>

                        {{-- CTA --}}
                        <a href="{{ route('properties.show', $property->id) }}"
                            class="inline-block mt-5 w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-10">
            {{ $properties->links() }}
        </div>
    @empty
        {{-- EMPTY STATE --}}
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🏠</div>
            <h3 class="text-xl font-semibold text-gray-700">
                No properties found
            </h3>
            <p class="text-gray-500 mt-2">
                Try adjusting your filters or search keywords
            </p>
        </div>
    @endforelse

</x-public>