<x-public>
    <x-hero-slider />

    {{-- Use navbar component --}}

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-24 md:py-32 grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 md:space-y-8">
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 leading-tight">
                Find Your Dream Property
            </h1>
            <p class="text-gray-600 text-lg md:text-xl">
                Smart real estate suggestions for buyers, sellers, and agents. Browse listings, manage your properties,
                or get recommendations.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="/properties/latest"
                    class="px-8 py-3 bg-linear-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold shadow-lg hover:scale-105 transform transition">
                    Browse Properties
                </a>
                <a href="/buy/residential"
                    class="px-8 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 font-semibold transition">
                    Start Buying
                </a>
            </div>
        </div>
        <div class="relative">
            <img src="{{ asset('images/image3.jpg') }}" alt="Luxury home" class="rounded-xl shadow-2xl">
            {{-- Optional small floating badge --}}
            <div
                class="absolute top-6 right-6 bg-white rounded-full px-4 py-2 shadow-md text-sm font-medium text-gray-700">
                Featured Listing
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8 text-center">
            <div class="p-8 bg-white rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <h3 class="text-2xl font-semibold mb-4">For Buyers</h3>
                <p class="text-gray-600">Recommend properties based on your budget and preferences.</p>
            </div>
            <div class="p-8 bg-white rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <h3 class="text-2xl font-semibold mb-4">For Sellers</h3>
                <p class="text-gray-600">List and manage your properties easily and efficiently.</p>
            </div>
            <div class="p-8 bg-white rounded-2xl shadow hover:shadow-xl transition transform hover:-translate-y-1">
                <h3 class="text-2xl font-semibold mb-4">For Agents</h3>
                <p class="text-gray-600">Connect with buyers and sellers and manage your deals efficiently.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    {{-- <footer class="text-center py-10 text-gray-500">
        © {{ date('Y') }} RealEstate. All rights reserved.
    </footer> --}}

</x-public>