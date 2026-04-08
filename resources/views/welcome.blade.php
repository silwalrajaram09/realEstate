<x-public>

    {{-- ── HERO SLIDER ── --}}
    <x-hero-slider />

    {{-- ── INTRO / VALUE PROP ── --}}
    <section class="bg-[#faf7f2] pt-28 pb-20">
        <div class="max-w-7xl mx-auto px-8 md:px-14">

            <div class="reveal flex items-center gap-3 mb-4">
                <div class="w-8 h-[1px] bg-[#c9a96e]"></div>
                <span class="text-[0.68rem] tracking-[0.16em] uppercase text-[#c9a96e] font-medium">Why Choose Us</span>
            </div>

            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="reveal font-['Cormorant_Garamond'] text-[clamp(2.4rem,4.5vw,3.5rem)] font-semibold leading-[1.1] text-[#0f0f0f] mb-6">
                        Smarter Real Estate,<br><em class="text-[#c9a96e]">Built Around You</em>
                    </h2>
                    <p class="reveal reveal-delay-1 text-[#5a5048] text-[1.05rem] font-light leading-[1.8] mb-8">
                        Our platform blends intelligent recommendations with an effortless browsing experience — helping buyers find the right home and sellers reach the right audience.
                    </p>
                    <a href="{{ route('register') }}" class="reveal reveal-delay-2 inline-flex items-center gap-2 text-[0.8rem] font-semibold tracking-[0.1em] uppercase text-[#0f0f0f] no-underline border-b-[1.5px] border-[#c9a96e] pb-[3px] transition-colors duration-200 hover:text-[#c9a96e]">
                        Create Free Account
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Feature image with floating badge --}}
                <div class="reveal reveal-delay-2 relative">
                    <img src="{{ asset('images/image2.jpg') }}" alt="Modern interior"
                         class="w-full rounded object-cover h-[420px] shadow-[0_24px_60px_rgba(0,0,0,0.12)]">
                    <div class="absolute -bottom-6 -left-6 bg-white rounded py-5 px-6 shadow-[0_8px_30px_rgba(0,0,0,0.1)] flex items-center gap-4 min-w-[220px]">
                        <div class="w-12 h-12 rounded-full bg-[linear-gradient(135deg,#c9a96e,#9a7340)] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-['Cormorant_Garamond'] text-[1.5rem] font-semibold leading-none text-[#0f0f0f]">98%</div>
                            <div class="text-[0.72rem] tracking-[0.06em] text-[#8c8070] mt-[2px]">Match Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SERVICES SECTION ── --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-8 md:px-14">

            <div class="reveal text-center mb-14">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-8 h-[1px] bg-[#c9a96e]"></div>
                    <span class="text-[0.68rem] tracking-[0.16em] uppercase text-[#c9a96e] font-medium">Services</span>
                    <div class="w-8 h-[1px] bg-[#c9a96e]"></div>
                </div>
                <h2 class="font-['Cormorant_Garamond'] text-[clamp(2rem,4vw,3rem)] font-semibold text-[#0f0f0f]">
                    Everything You Need
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">

                {{-- Card 1: Buyers --}}
                <div class="reveal reveal-delay-1 group border border-[#ede8df] rounded px-8 py-10 transition-all duration-300 bg-[#faf7f2] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-[3px] bg-[linear-gradient(135deg,#c9a96e22,#c9a96e11)] border border-[#c9a96e44] flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <h3 class="font-['Cormorant_Garamond'] text-[1.5rem] font-semibold text-[#0f0f0f] mb-3">For Buyers</h3>
                    <p class="text-[#6b5e52] text-[0.925rem] font-light leading-[1.75] mb-6">
                        Get AI-matched property suggestions based on your budget, location preferences, and lifestyle needs.
                    </p>
                    <a href="{{ route('buyer.properties') }}"
                       class="text-[0.75rem] tracking-[0.1em] uppercase font-semibold text-[#c9a96e] no-underline inline-flex items-center gap-1.5 transition-all duration-300 hover:gap-2.5">
                        Browse Listings →
                    </a>
                </div>

                {{-- Card 2: Sellers --}}
                <div class="reveal reveal-delay-2 group border border-[#ede8df] rounded px-8 py-10 transition-all duration-300 bg-[#faf7f2] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-[3px] bg-[linear-gradient(135deg,#c9a96e22,#c9a96e11)] border border-[#c9a96e44] flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="font-['Cormorant_Garamond'] text-[1.5rem] font-semibold text-[#0f0f0f] mb-3">For Sellers</h3>
                    <p class="text-[#6b5e52] text-[0.925rem] font-light leading-[1.75] mb-6">
                        List your property with ease, manage inquiries, and reach thousands of pre-qualified buyers instantly.
                    </p>
                    <a href="{{ route('seller.properties.create') }}"
                       class="text-[0.75rem] tracking-[0.1em] uppercase font-semibold text-[#c9a96e] no-underline inline-flex items-center gap-1.5 transition-all duration-300 hover:gap-2.5">
                        List a Property →
                    </a>
                </div>

                {{-- Card 3: AI Suggestions --}}
                <div class="reveal reveal-delay-3 group border border-[#ede8df] rounded px-8 py-10 transition-all duration-300 bg-[#faf7f2] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-[3px] bg-[linear-gradient(135deg,#c9a96e22,#c9a96e11)] border border-[#c9a96e44] flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 class="font-['Cormorant_Garamond'] text-[1.5rem] font-semibold text-[#0f0f0f] mb-3">AI Suggestions</h3>
                    <p class="text-[#6b5e52] text-[0.925rem] font-light leading-[1.75] mb-6">
                        Our intelligent engine learns your preferences and surfaces curated recommendations you'll actually love.
                    </p>
                    <a href="{{ route('buyer.suggestions') }}"
                       class="text-[0.75rem] tracking-[0.1em] uppercase font-semibold text-[#c9a96e] no-underline inline-flex items-center gap-1.5 transition-all duration-300 hover:gap-2.5">
                        See Suggestions →
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FEATURED LISTING TEASER ── --}}
    <section class="bg-[#faf7f2] py-24">
        <div class="max-w-7xl mx-auto px-8 md:px-14">
            <div class="reveal flex items-center justify-between mb-12 flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-[1px] bg-[#c9a96e]"></div>
                        <span class="text-[0.68rem] tracking-[0.16em] uppercase text-[#c9a96e] font-medium">Portfolio</span>
                    </div>
                    <h2 class="font-['Cormorant_Garamond'] text-[clamp(2rem,4vw,3rem)] font-semibold text-[#0f0f0f]">
                        Featured Properties
                    </h2>
                </div>
                <a href="{{ url('/properties') }}"
                   class="text-[0.78rem] tracking-[0.1em] uppercase font-semibold text-[#0f0f0f] no-underline border-b-[1.5px] border-[#c9a96e] pb-[3px]">
                    View All →
                </a>
            </div>

            {{-- Asymmetric image grid --}}
            <div class="grid md:grid-cols-3 gap-5">
                <div class="reveal reveal-delay-1 md:col-span-2 relative overflow-hidden group rounded h-[420px]">
                    <img src="{{ asset('images/image1.jpg') }}" alt="Featured"
                         class="w-full h-full object-cover transition-transform duration-600 ease-out group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent via-black/30"></div>
                    <div class="absolute bottom-7 left-7">
                        <span class="text-[0.65rem] tracking-[0.12em] uppercase bg-[#c9a96e] text-[#0f0f0f] px-3 py-1 rounded-sm font-semibold">Featured</span>
                        <p class="font-['Cormorant_Garamond'] text-[1.5rem] text-white font-semibold mt-2">Luxury Hillside Villa</p>
                        <p class="text-white/70 text-[0.85rem]">4 bed · 3 bath · 3,200 sqft</p>
                    </div>
                </div>

                <div class="reveal reveal-delay-2 flex flex-col gap-5">
                    <div class="relative overflow-hidden group rounded flex-1">
                        <img src="{{ asset('images/image2.jpg') }}" alt="Modern home"
                             class="w-full h-full min-h-[195px] object-cover transition-transform duration-600 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent via-black/25"></div>
                        <div class="absolute bottom-4 left-4">
                            <p class="font-['Cormorant_Garamond'] text-[1.15rem] text-white font-semibold">Modern City Apartment</p>
                            <p class="text-white/70 text-[0.78rem]">2 bed · 2 bath</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden group rounded flex-1">
                        <img src="{{ asset('images/image3.jpg') }}" alt="Dream property"
                             class="w-full h-full min-h-[195px] object-cover transition-transform duration-600 ease-out group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/55 to-transparent via-black/25"></div>
                        <div class="absolute bottom-4 left-4">
                            <p class="font-['Cormorant_Garamond'] text-[1.15rem] text-white font-semibold">Garden Suburban Home</p>
                            <p class="text-white/70 text-[0.78rem]">3 bed · 2 bath</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA BANNER ── --}}
    <section class="bg-[#0f0f0f] py-24 relative overflow-hidden">
        {{-- Decorative gold accent --}}
        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-[#c9a96e] to-transparent bg-[length:100%_100%]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_50%,rgba(201,169,110,0.07)_0%,transparent_65%)]"></div>

        <div class="max-w-7xl mx-auto px-8 md:px-14 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="reveal flex items-center gap-3 mb-5">
                        <div class="w-8 h-[1px] bg-[#c9a96e]"></div>
                        <span class="text-[0.68rem] tracking-[0.16em] uppercase text-[#c9a96e] font-medium">Get Started</span>
                    </div>
                    <h2 class="reveal font-['Cormorant_Garamond'] text-[clamp(2.2rem,4vw,3.25rem)] font-semibold text-white leading-[1.1]">
                        Ready to Find Your<br><em class="text-[#c9a96e]">Perfect Home?</em>
                    </h2>
                </div>
                <div class="reveal reveal-delay-2">
                    <p class="text-white/60 text-[1rem] font-light leading-[1.8] mb-8">
                        Join thousands of buyers and sellers already using our platform to make smarter real estate decisions.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-9 py-3.5 bg-[#c9a96e] hover:bg-[#b5924f] text-[#0f0f0f] font-semibold text-[0.875rem] tracking-[0.04em] rounded-[3px] no-underline transition-colors duration-200">
                            Create Account
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 px-9 py-3.5 border border-white/20 hover:border-white/50 text-white/80 hover:text-white font-[0.875rem] rounded-[3px] no-underline transition-all duration-200">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-[#c9a96e] to-transparent"></div>
    </section>

</x-public>