<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-blue-600 dark:bg-blue-700 rounded-lg shadow-lg mb-8 p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Welcome to RealEstateSuggester!</h1>
                <p class="text-blue-100 dark:text-blue-200">Find your dream property from our extensive collection of
                    listings.</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Properties</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $stats['total_properties'] ?? 0 }}
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">For Sale</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $stats['for_sale'] ?? 0 }}
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">For Rent</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            {{ $stats['for_rent'] ?? 0 }}
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Price</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">Rs
                            {{ number_format($stats['avg_price'] ?? 0) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Properties -->
            @if(isset($recentProperties) && $recentProperties->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Featured Properties</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($recentProperties as $property)
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 rounded-lg overflow-hidden hover:shadow-md transition border border-gray-200 dark:border-gray-600">
                                    <div class="h-40 bg-gray-200 dark:bg-gray-600">
                                        @if($property->image)
                                            <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $property->title }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $property->location }}</p>
                                        <div class="flex items-center justify-between mt-3">
                                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">Rs
                                                {{ number_format($property->price) }}</span>
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                                @if($property->purpose === 'buy')
                                                                                    bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400
                                                                                @elseif($property->purpose === 'sell')
                                                                                    bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400
                                                                                @else
                                                                                    bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400
                                                                                @endif">
                                                {{ ucfirst($property->purpose) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('buyer.properties.show', $property->id) }}"
                                            class="mt-3 block text-center w-full py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition dark:bg-blue-700 dark:hover:bg-blue-600">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>