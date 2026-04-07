<x-public>

    {{-- ── HERO SLIDER ── --}}
    <x-hero-slider />

    {{-- ── INTRO / VALUE PROP ── --}}
    <section style="background:#faf7f2; padding:7rem 0 5rem;">
        <div class="max-w-7xl mx-auto px-8 md:px-14">

            <div class="reveal flex items-center gap-3 mb-4">
                <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                <span style="font-size:0.68rem; letter-spacing:0.16em; text-transform:uppercase; color:#c9a96e; font-weight:500;">Why Choose Us</span>
            </div>

            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="reveal" style="font-family:'Cormorant Garamond',serif; font-size:clamp(2.4rem,4.5vw,3.5rem); font-weight:600; line-height:1.1; color:#0f0f0f; margin-bottom:1.5rem;">
                        Smarter Real Estate,<br><em style="color:#c9a96e;">Built Around You</em>
                    </h2>
                    <p class="reveal reveal-delay-1" style="color:#5a5048; font-size:1.05rem; font-weight:300; line-height:1.8; margin-bottom:2rem;">
                        Our platform blends intelligent recommendations with an effortless browsing experience — helping buyers find the right home and sellers reach the right audience.
                    </p>
                    <a href="{{ route('register') }}" class="reveal reveal-delay-2"
                       style="display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8rem; font-weight:600;
                              letter-spacing:0.1em; text-transform:uppercase; color:#0f0f0f; text-decoration:none;
                              border-bottom:1.5px solid #c9a96e; padding-bottom:3px; transition:color 0.2s ease;"
                       onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='#0f0f0f'">
                        Create Free Account
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                {{-- Feature image with floating badge --}}
                <div class="reveal reveal-delay-2 relative">
                    <img src="{{ asset('images/image2.jpg') }}" alt="Modern interior"
                         style="width:100%; border-radius:4px; object-fit:cover; height:420px; box-shadow:0 24px 60px rgba(0,0,0,0.12);">
                    <div style="position:absolute; bottom:-1.5rem; left:-1.5rem; background:#fff;
                                border-radius:4px; padding:1.25rem 1.5rem; box-shadow:0 8px 30px rgba(0,0,0,0.1);
                                display:flex; align-items:center; gap:1rem; min-width:220px;">
                        <div style="width:3rem; height:3rem; border-radius:50%; background:linear-gradient(135deg,#c9a96e,#9a7340);
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; line-height:1; color:#0f0f0f;">98%</div>
                            <div style="font-size:0.72rem; letter-spacing:0.06em; color:#8c8070; margin-top:2px;">Match Satisfaction</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SERVICES SECTION ── --}}
    <section style="background:#fff; padding:6rem 0;">
        <div class="max-w-7xl mx-auto px-8 md:px-14">

            <div class="reveal text-center mb-14">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                    <span style="font-size:0.68rem; letter-spacing:0.16em; text-transform:uppercase; color:#c9a96e; font-weight:500;">Services</span>
                    <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                </div>
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,4vw,3rem); font-weight:600; color:#0f0f0f;">
                    Everything You Need
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">

                {{-- Card 1: Buyers --}}
                <div class="reveal reveal-delay-1 group" style="border:1px solid #ede8df; border-radius:4px; padding:2.5rem 2rem;
                     transition:box-shadow 0.3s ease, transform 0.3s ease; background:#faf7f2;"
                     onmouseover="this.style.boxShadow='0 20px 50px rgba(0,0,0,0.08)'; this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                    <div style="width:3rem; height:3rem; border-radius:3px; background:linear-gradient(135deg,#c9a96e22,#c9a96e11);
                                border:1px solid #c9a96e44; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.75rem;">For Buyers</h3>
                    <p style="color:#6b5e52; font-size:0.925rem; font-weight:300; line-height:1.75; margin-bottom:1.5rem;">
                        Get AI-matched property suggestions based on your budget, location preferences, and lifestyle needs.
                    </p>
                    <a href="{{ route('buyer.properties') }}"
                       style="font-size:0.75rem; letter-spacing:0.1em; text-transform:uppercase; font-weight:600;
                              color:#c9a96e; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;"
                       onmouseover="this.style.gap='0.6rem'" onmouseout="this.style.gap='0.375rem'">
                        Browse Listings →
                    </a>
                </div>

                {{-- Card 2: Sellers --}}
                <div class="reveal reveal-delay-2 group" style="border:1px solid #ede8df; border-radius:4px; padding:2.5rem 2rem;
                     transition:box-shadow 0.3s ease, transform 0.3s ease; background:#faf7f2;"
                     onmouseover="this.style.boxShadow='0 20px 50px rgba(0,0,0,0.08)'; this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                    <div style="width:3rem; height:3rem; border-radius:3px; background:linear-gradient(135deg,#c9a96e22,#c9a96e11);
                                border:1px solid #c9a96e44; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.75rem;">For Sellers</h3>
                    <p style="color:#6b5e52; font-size:0.925rem; font-weight:300; line-height:1.75; margin-bottom:1.5rem;">
                        List your property with ease, manage inquiries, and reach thousands of pre-qualified buyers instantly.
                    </p>
                    <a href="{{ route('seller.properties.create') }}"
                       style="font-size:0.75rem; letter-spacing:0.1em; text-transform:uppercase; font-weight:600;
                              color:#c9a96e; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;"
                       onmouseover="this.style.gap='0.6rem'" onmouseout="this.style.gap='0.375rem'">
                        List a Property →
                    </a>
                </div>

                {{-- Card 3: AI Suggestions --}}
                <div class="reveal reveal-delay-3 group" style="border:1px solid #ede8df; border-radius:4px; padding:2.5rem 2rem;
                     transition:box-shadow 0.3s ease, transform 0.3s ease; background:#faf7f2;"
                     onmouseover="this.style.boxShadow='0 20px 50px rgba(0,0,0,0.08)'; this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                    <div style="width:3rem; height:3rem; border-radius:3px; background:linear-gradient(135deg,#c9a96e22,#c9a96e11);
                                border:1px solid #c9a96e44; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#c9a96e" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600; color:#0f0f0f; margin-bottom:0.75rem;">AI Suggestions</h3>
                    <p style="color:#6b5e52; font-size:0.925rem; font-weight:300; line-height:1.75; margin-bottom:1.5rem;">
                        Our intelligent engine learns your preferences and surfaces curated recommendations you'll actually love.
                    </p>
                    <a href="{{ route('buyer.suggestions') }}"
                       style="font-size:0.75rem; letter-spacing:0.1em; text-transform:uppercase; font-weight:600;
                              color:#c9a96e; text-decoration:none; display:inline-flex; align-items:center; gap:0.375rem;"
                       onmouseover="this.style.gap='0.6rem'" onmouseout="this.style.gap='0.375rem'">
                        See Suggestions →
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FEATURED LISTING TEASER ── --}}
    <section style="background:#faf7f2; padding:6rem 0;">
        <div class="max-w-7xl mx-auto px-8 md:px-14">
            <div class="reveal flex items-center justify-between mb-12 flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                        <span style="font-size:0.68rem; letter-spacing:0.16em; text-transform:uppercase; color:#c9a96e; font-weight:500;">Portfolio</span>
                    </div>
                    <h2 style="font-family:'Cormorant Garamond',serif; font-size:clamp(2rem,4vw,3rem); font-weight:600; color:#0f0f0f;">
                        Featured Properties
                    </h2>
                </div>
                <a href="{{ url('/properties') }}"
                   style="font-size:0.78rem; letter-spacing:0.1em; text-transform:uppercase; font-weight:600;
                          color:#0f0f0f; text-decoration:none; border-bottom:1.5px solid #c9a96e; padding-bottom:3px;">
                    View All →
                </a>
            </div>

            {{-- Asymmetric image grid --}}
            <div class="grid md:grid-cols-3 gap-5">
                <div class="reveal reveal-delay-1 md:col-span-2 relative overflow-hidden group"
                     style="border-radius:4px; height:420px;">
                    <img src="{{ asset('images/image1.jpg') }}" alt="Featured"
                         style="width:100%; height:100%; object-fit:cover; transition:transform 0.6s ease;"
                         onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 55%);"></div>
                    <div style="position:absolute; bottom:1.75rem; left:1.75rem;">
                        <span style="font-size:0.65rem; letter-spacing:0.12em; text-transform:uppercase;
                                     background:#c9a96e; color:#0f0f0f; padding:0.25rem 0.75rem; border-radius:2px; font-weight:600;">Featured</span>
                        <p style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; color:#fff; font-weight:600; margin-top:0.5rem;">Luxury Hillside Villa</p>
                        <p style="color:rgba(255,255,255,0.7); font-size:0.85rem;">4 bed · 3 bath · 3,200 sqft</p>
                    </div>
                </div>

                <div class="reveal reveal-delay-2 flex flex-col gap-5">
                    <div class="relative overflow-hidden group" style="border-radius:4px; flex:1;">
                        <img src="{{ asset('images/image2.jpg') }}" alt="Modern home"
                             style="width:100%; height:100%; min-height:195px; object-fit:cover; transition:transform 0.6s ease;"
                             onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);"></div>
                        <div style="position:absolute; bottom:1rem; left:1rem;">
                            <p style="font-family:'Cormorant Garamond',serif; font-size:1.15rem; color:#fff; font-weight:600;">Modern City Apartment</p>
                            <p style="color:rgba(255,255,255,0.7); font-size:0.78rem;">2 bed · 2 bath</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden group" style="border-radius:4px; flex:1;">
                        <img src="{{ asset('images/image3.jpg') }}" alt="Dream property"
                             style="width:100%; height:100%; min-height:195px; object-fit:cover; transition:transform 0.6s ease;"
                             onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform='scale(1)'">
                        <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);"></div>
                        <div style="position:absolute; bottom:1rem; left:1rem;">
                            <p style="font-family:'Cormorant Garamond',serif; font-size:1.15rem; color:#fff; font-weight:600;">Garden Suburban Home</p>
                            <p style="color:rgba(255,255,255,0.7); font-size:0.78rem;">3 bed · 2 bath</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA BANNER ── --}}
    <section style="background:#0f0f0f; padding:6rem 0; position:relative; overflow:hidden;">
        {{-- Decorative gold accent --}}
        <div style="position:absolute; top:0; left:0; right:0; height:1px; background:linear-gradient(to right, transparent, #c9a96e 40%, #c9a96e 60%, transparent);"></div>
        <div style="position:absolute; inset:0; background: radial-gradient(ellipse at 70% 50%, rgba(201,169,110,0.07) 0%, transparent 65%);"></div>

        <div class="max-w-7xl mx-auto px-8 md:px-14 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="reveal flex items-center gap-3 mb-5">
                        <div style="width:2rem; height:1px; background:#c9a96e;"></div>
                        <span style="font-size:0.68rem; letter-spacing:0.16em; text-transform:uppercase; color:#c9a96e; font-weight:500;">Get Started</span>
                    </div>
                    <h2 class="reveal" style="font-family:'Cormorant Garamond',serif; font-size:clamp(2.2rem,4vw,3.25rem); font-weight:600; color:#fff; line-height:1.1;">
                        Ready to Find Your<br><em style="color:#c9a96e;">Perfect Home?</em>
                    </h2>
                </div>
                <div class="reveal reveal-delay-2">
                    <p style="color:rgba(255,255,255,0.6); font-size:1rem; font-weight:300; line-height:1.8; margin-bottom:2rem;">
                        Join thousands of buyers and sellers already using our platform to make smarter real estate decisions.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.9rem 2.25rem;
                                  background:#c9a96e; color:#0f0f0f; font-weight:600; font-size:0.875rem;
                                  letter-spacing:0.04em; border-radius:3px; text-decoration:none;
                                  transition:background 0.2s ease;"
                           onmouseover="this.style.background='#b5924f'" onmouseout="this.style.background='#c9a96e'">
                            Create Account
                        </a>
                        <a href="{{ route('login') }}"
                           style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.9rem 2.25rem;
                                  border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.8); font-size:0.875rem;
                                  border-radius:3px; text-decoration:none; transition:border-color 0.2s ease, color 0.2s ease;"
                           onmouseover="this.style.borderColor='rgba(255,255,255,0.5)'; this.style.color='#fff'"
                           onmouseout="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.color='rgba(255,255,255,0.8)'">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div style="position:absolute; bottom:0; left:0; right:0; height:1px; background:linear-gradient(to right, transparent, #c9a96e 40%, #c9a96e 60%, transparent);"></div>
    </section>

</x-public>