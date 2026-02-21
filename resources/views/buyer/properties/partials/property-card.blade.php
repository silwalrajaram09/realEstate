<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group property-card"
    data-property-id="{{ $property->id }}">
    {{-- Image Section --}}
    <div class="relative h-52 bg-gray-200 overflow-hidden">
        <img src="{{ $property->image_url }}" alt="{{ $property->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                {{ $property->purpose === 'rent' ? 'bg-purple-600' : 'bg-green-600' }} text-white">
                {{ $property->purpose_label }}
            </span>
            @if($property->type)
                <span
                    class="px-3 py-1 text-xs font-medium bg-white/90 backdrop-blur-sm rounded-full text-gray-700 shadow-sm">
                    {{ $property->type_label }}
                </span>
            @endif
            @if($property->is_new)
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-600 text-white">
                    New
                </span>
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