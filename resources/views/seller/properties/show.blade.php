<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('seller.properties.index') }}"
                    class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to List
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('seller.properties.edit', $property->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Edit Property
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <!-- Property Image -->
                <div class="h-64 bg-gray-200">
                    @if($property->image)
                        <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h1>
                            <div class="flex items-center gap-2 text-gray-500 mt-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span>{{ $property->location }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-blue-600">Rs {{ number_format($property->price) }}</p>
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($property->purpose === 'buy') bg-green-100 text-green-700
                                @else bg-blue-100 text-blue-700 @endif">
                                For {{ ucfirst($property->purpose) }}
                            </span>
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-700 rounded-full">
                            {{ ucfirst($property->type) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-700 rounded-full">
                            {{ ucfirst($property->category) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($property->status === 'approved') bg-green-100 text-green-700
                            @elseif($property->status === 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>

                    <!-- Description -->
                    @if($property->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-600">{{ $property->description }}</p>
                        </div>
                    @endif

                    <!-- Features -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @if($property->bedrooms)
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $property->bedrooms }}</p>
                                <p class="text-sm text-gray-500">Bedrooms</p>
                            </div>
                        @endif
                        @if($property->bathrooms)
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $property->bathrooms }}</p>
                                <p class="text-sm text-gray-500">Bathrooms</p>
                            </div>
                        @endif
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $property->area }}</p>
                            <p class="text-sm text-gray-500">Sq.ft</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-2xl font-bold text-gray-900">{{ $property->area * 9 }}</p>
                            <p class="text-sm text-gray-500">Sq.m</p>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Amenities</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center gap-2">
                                @if($property->parking)
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Parking Available</span>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-gray-500">No Parking</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($property->water)
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700">Water Supply</span>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-gray-500">No Water Supply</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="border-t pt-6 mt-6 text-sm text-gray-500">
                        <p>Listed on: {{ $property->created_at->format('M d, Y') }}</p>
                        <p>Last updated: {{ $property->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>