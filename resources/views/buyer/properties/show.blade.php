<x-app-layout>
    @php
        $hasImage = !empty($property->image);
        $isBuy = $property->purpose === 'buy';
    @endphp

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- ================= MAIN CONTENT ================= -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                        <!-- Image -->
                        <div class="relative h-[420px] bg-gray-100">
                            @if($hasImage)
                                <img
                                    src="{{ asset('images/'.$property->image) }}"
                                    alt="{{ $property->title }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M3 12l2-2 7-7 7 7M5 10v10h14V10"/>
                                    </svg>
                                </div>
                            @endif

                            <span class="absolute top-5 left-5 px-5 py-2 text-sm font-semibold rounded-full shadow
                                {{ $isBuy ? 'bg-emerald-600' : 'bg-blue-600' }} text-white">
                                For {{ ucfirst($property->purpose) }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="p-8">

                            <!-- Title -->
                            <h1 class="text-3xl font-extrabold text-gray-900">
                                {{ $property->title }}
                            </h1>

                            <div class="flex items-center gap-2 text-gray-500 mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 11c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/>
                                </svg>
                                <span>{{ $property->location }}</span>
                            </div>

                            <!-- Badges -->
                            <div class="flex flex-wrap gap-3 mt-6">
                                <span class="badge">{{ ucfirst($property->type) }}</span>
                                <span class="badge">{{ ucfirst($property->category) }}</span>

                                @if($property->status === 'approved')
                                    <span class="px-4 py-2 text-sm bg-green-100 text-green-700 rounded-full font-medium">
                                        ✔ Verified
                                    </span>
                                @endif
                            </div>

                            <!-- Features -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-10">

                                @if($property->bedrooms)
                                    <div class="feature-card">
                                        <p class="feature-value">{{ $property->bedrooms }}</p>
                                        <p class="feature-label">Bedrooms</p>
                                    </div>
                                @endif

                                @if($property->bathrooms)
                                    <div class="feature-card">
                                        <p class="feature-value">{{ $property->bathrooms }}</p>
                                        <p class="feature-label">Bathrooms</p>
                                    </div>
                                @endif

                                <div class="feature-card">
                                    <p class="feature-value">{{ $property->area }}</p>
                                    <p class="feature-label">Sq.ft</p>
                                </div>

                                <div class="feature-card">
                                    <p class="feature-value">{{ $property->created_at->diffForHumans() }}</p>
                                    <p class="feature-label">Listed</p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($property->description)
                                <div class="mt-10">
                                    <h2 class="text-xl font-bold mb-3">Description</h2>
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $property->description }}
                                    </p>
                                </div>
                            @endif

                            <!-- Amenities -->
                            <div class="mt-10 border-t pt-8">
                                <h2 class="text-xl font-bold mb-4">Amenities</h2>

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach([
                                        'Parking' => $property->parking,
                                        'Water Supply' => $property->water
                                    ] as $label => $value)
                                        <div class="flex items-center gap-3 p-4 rounded-lg
                                            {{ $value ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            <span class="font-medium">{{ $label }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ================= SIDEBAR ================= -->
                <div class="space-y-6">

                    <!-- Price -->
                    <div class="bg-white rounded-2xl shadow-md sticky top-6">
                        <div class="p-7">
                            <p class="text-sm text-gray-500">Price</p>
                            <p class="text-4xl font-extrabold text-blue-600 mb-6">
                                Rs {{ number_format($property->price) }}
                            </p>

                            <button class="primary-btn">
                                Contact Seller
                            </button>

                            <button class="secondary-btn mt-3">
                                Save to Favorites
                            </button>
                        </div>
                    </div>

                    <!-- Seller -->
                    <div class="bg-white rounded-2xl shadow-md">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-5">Seller Information</h3>

                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="font-bold text-blue-600">
                                        {{ strtoupper(substr($property->seller->name ?? 'NA', 0, 2)) }}
                                    </span>
                                </div>

                                <div>
                                    <p class="font-semibold">{{ $property->seller->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">Property Owner</p>
                                </div>
                            </div>

                            <button class="w-full py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                                Call Now
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ================= RECOMMENDATIONS ================= -->
            @if(isset($recommendations) && $recommendations->count())
                <div class="mt-16">
                    <h2 class="text-2xl font-bold mb-6">Similar Properties</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($recommendations as $rec)
                            <a href="{{ route('buyer.properties.show', $rec->id) }}"
                               class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">

                                <div class="h-48 bg-gray-100">
                                    @if($rec->image)
                                        <img src="{{ asset('images/'.$rec->image) }}"
                                             class="w-full h-full object-cover"
                                             loading="lazy">
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="font-semibold">{{ $rec->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $rec->location }}</p>
                                    <p class="text-lg font-bold text-blue-600 mt-2">
                                        Rs {{ number_format($rec->price) }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Utility Classes -->
    <style>
        .badge {
            @apply px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-full;
        }
        .feature-card {
            @apply bg-gray-50 rounded-xl p-5 text-center;
        }
        .feature-value {
            @apply text-2xl font-bold text-gray-900;
        }
        .feature-label {
            @apply text-sm text-gray-500;
        }
        .primary-btn {
            @apply w-full py-4 bg-blue-600 text-white rounded-xl font-semibold text-lg hover:bg-blue-700 transition;
        }
        .secondary-btn {
            @apply w-full py-4 border-2 border-blue-600 text-blue-600 rounded-xl font-semibold text-lg hover:bg-blue-50 transition;
        }
    </style>
</x-app-layout>
