@props(['property', 'showScore' => false])

<div
    class="bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden hover:shadow-md transition border border-gray-200 dark:border-gray-600 relative group">

    <!-- Match Score Badge (optional) -->
    @if($showScore && isset($property->relevance_score))
        <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full z-10 shadow-lg">
            {{ round($property->relevance_score) }}% Match
        </div>
    @endif

    <!-- Save to Favorites Button -->
    <button onclick="toggleFavorite({{ $property->id }})"
        class="absolute top-2 left-2 w-8 h-8 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition z-10 favorite-btn"
        data-property-id="{{ $property->id }}">
        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Property Image -->
    <div class="h-48 bg-gray-200 dark:bg-gray-600 overflow-hidden">

        <img src="{{ $property->image_url }}" alt="{{ $property->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">

    </div>

    <!-- Property Details -->
    <div class="p-4">
        <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $property->title }}</h3>

        <!-- Location -->
        <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="truncate">{{ $property->location }}</span>
        </div>

        <!-- Features -->
        <div class="mt-3 flex flex-wrap gap-1">
            @if($property->bedrooms)
                <span
                    class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs rounded">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ $property->bedrooms }} BHK
                </span>
            @endif

            @if($property->bathrooms)
                <span
                    class="inline-flex items-center px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    {{ $property->bathrooms }}
                </span>
            @endif

            @if($property->area)
                <span
                    class="inline-flex items-center px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs rounded">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                    {{ $property->area }} sq.ft
                </span>
            @endif
        </div>

        <!-- Price and Purpose -->
        <div class="flex items-center justify-between mt-4">
            <div>
                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                    Rs {{ number_format($property->price) }}
                </span>
                @if($property->purpose === 'rent')
                    <span class="text-xs text-gray-500 dark:text-gray-400">/month</span>
                @endif
            </div>
            <span class="px-2 py-1 text-xs font-medium rounded-full
                @if($property->purpose === 'buy')
                    bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400
                @else
                    bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400
                @endif">
                {{ $property->purpose === 'buy' ? 'For Sale' : 'For Rent' }}
            </span>
        </div>

        <!-- View Details Button -->
        <a href="{{ route('buyer.properties.show', $property->id) }}"
            class="mt-4 block text-center w-full py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition dark:bg-blue-700 dark:hover:bg-blue-600">
            View Details
        </a>
    </div>
</div>

<script>
    function toggleFavorite(propertyId) {
        // Add your favorite toggle logic here
        fetch(`/buyer/favorites/${propertyId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Toggle heart icon
                    const btn = document.querySelector(`.favorite-btn[data-property-id="${propertyId}"] svg`);
                    if (data.is_favorite) {
                        btn.classList.add('text-red-500');
                        btn.classList.remove('text-gray-400');
                    } else {
                        btn.classList.remove('text-red-500');
                        btn.classList.add('text-gray-400');
                    }
                }
            });
    }
</script>