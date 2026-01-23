<section class="relative w-full h-screen min-h-150 overflow-hidden">

    {{-- Slides --}}
    <div id="slider" class="relative w-full h-full">

        {{-- Slide 1 (active) --}}
        <div class="slide absolute inset-0 opacity-100 scale-100 transition-all duration-1000 ease-in-out">
            <img
                src="{{ asset('images/image1.jpg') }}"
                alt="Luxury property"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- Slide 2 --}}
        <div class="slide absolute inset-0 opacity-0 scale-110 transition-all duration-1000 ease-in-out">
            <img
                src="{{ asset('images/image2.jpg') }}"
                alt="Modern home"
                class="w-full h-full object-cover"
            >
        </div>

        {{-- Slide 3 --}}
        <div class="slide absolute inset-0 opacity-0 scale-110 transition-all duration-1000 ease-in-out">
            <img
                src="{{ asset('images/image3.jpg') }}"
                alt="Dream property"
                class="w-full h-full object-cover"
            >
        </div>
    </div>

    {{-- Gradient Overlay --}}
    <div class="absolute inset-0 bg-linear-to-b from-black/60 via-black/50 to-black/70"></div>

    {{-- Content --}}
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white px-6 max-w-4xl animate-fadeIn">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Find Your Dream Property
            </h1>
            <p class="text-xl md:text-2xl mb-10 text-gray-200">
                Smart suggestions based on your needs
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="#search"
                    class="bg-blue-600 px-10 py-4 rounded-lg font-semibold text-lg hover:bg-blue-700 transition shadow-lg"
                >
                    Get Started
                </a>

                <a
                    href="{{ url('/properties') }}"
                    class="bg-white/10 backdrop-blur-sm border border-white px-10 py-4 rounded-lg font-semibold text-lg hover:bg-white/20 transition"
                >
                    View Properties
                </a>
            </div>
        </div>
    </div>

    {{-- Navigation Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex space-x-3 z-10">
        <button class="dot w-3 h-3 rounded-full bg-white" data-slide="0"></button>
        <button class="dot w-3 h-3 rounded-full bg-white/50" data-slide="1"></button>
        <button class="dot w-3 h-3 rounded-full bg-white/50" data-slide="2"></button>
    </div>

    {{-- Navigation Arrows --}}
    <button
        id="prevSlide"
        class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-4 rounded-full transition z-10"
    >
        ‹
    </button>

    <button
        id="nextSlide"
        class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-4 rounded-full transition z-10"
    >
        ›
    </button>

</section>
