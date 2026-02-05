<x-public>

    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- PROPERTY DETAILS --}}
        <div class="bg-white rounded-xl shadow p-6 mb-12">
            <h1 class="text-3xl font-bold text-gray-800">
                {{ $property->title }}
            </h1>

            <p class="text-gray-500 mt-2">
                📍 {{ $property->location }}
            </p>

            <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-700">
                <span class="bg-gray-100 px-3 py-1 rounded">
                    {{ ucfirst($property->purpose) }}
                </span>
                <span class="bg-gray-100 px-3 py-1 rounded">
                    {{ ucfirst($property->type) }}
                </span>
                <span class="bg-gray-100 px-3 py-1 rounded">
                    {{ ucfirst($property->category) }}
                </span>
            </div>

            <div class="mt-6 text-2xl font-semibold text-blue-600">
                Rs {{ number_format($property->price) }}
            </div>

            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-700">
                @if($property->bedrooms)
                    <div>🛏 {{ $property->bedrooms }} Bedrooms</div>
                @endif

                @if($property->bathrooms)
                    <div>🛁 {{ $property->bathrooms }} Bathrooms</div>
                @endif

                <div>📐 {{ $property->area }} sq ft</div>

                @if($property->parking)
                    <div>🚗 Parking</div>
                @endif

                @if($property->water)
                    <div>💧 Water Available</div>
                @endif
            </div>

            @if($property->description)
                <div class="mt-6 text-gray-600 leading-relaxed">
                    {{ $property->description }}
                </div>
            @endif
        </div>

        {{-- RECOMMENDATIONS --}}
        @if($recommendations->count())
            <h2 class="text-2xl font-bold mb-6">
                Similar Properties
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recommendations as $item)
                    <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
                        <h3 class="font-semibold text-gray-800">
                            {{ $item->title }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            📍 {{ $item->location }}
                        </p>

                        <p class="mt-2 font-semibold text-blue-600">
                            Rs {{ number_format($item->price) }}
                        </p>

                        <a href="{{ route('properties.show', $item->id) }}"
                            class="inline-block mt-3 text-sm text-blue-600 hover:underline">
                            View Details →
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</x-public>