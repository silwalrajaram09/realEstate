<footer style="background:#0f0f0f; color:rgba(255,255,255,0.55); font-family:'Outfit',sans-serif;">

    {{-- Top border accent --}}
    <div style="height:1px; background:linear-gradient(to right, transparent, #c9a96e 40%, #c9a96e 60%, transparent);"></div>

    <div class="max-w-7xl mx-auto px-8 md:px-14">

        {{-- Main footer grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 py-16">

            {{-- Brand column --}}
            <div class="col-span-2 md:col-span-1">
                <a href="{{ route('dashboard') }}"
                   style="font-family:'Cormorant Garamond',serif; font-size:1.5rem; font-weight:600;
                          color:#fff; text-decoration:none; letter-spacing:-0.01em; display:block; margin-bottom:1rem;">
                    Real<span style="color:#c9a96e;">Estate</span>
                </a>
                <p style="font-size:0.875rem; font-weight:300; line-height:1.8; color:rgba(255,255,255,0.45); max-width:200px;">
                    Intelligent property matching for modern buyers and sellers.
                </p>
                {{-- Social icons --}}
                <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                    @foreach([
                        ['M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z', 'Facebook'],
                        ['M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z', 'Twitter'],
                        ['M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z', 'LinkedIn'],
                    ] as [$path, $label])
                    <a href="#" aria-label="{{ $label }}"
                       style="width:2rem; height:2rem; border:1px solid rgba(255,255,255,0.12); border-radius:3px;
                              display:flex; align-items:center; justify-content:center;
                              transition:border-color 0.2s ease, background 0.2s ease;"
                       onmouseover="this.style.borderColor='#c9a96e'; this.style.background='rgba(201,169,110,0.1)'"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.12)'; this.style.background='transparent'">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="rgba(255,255,255,0.6)">
                            <path d="{{ $path }}"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Buyers --}}
            <div>
                <h4 style="font-size:0.68rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e;
                           font-weight:600; margin-bottom:1.25rem;">For Buyers</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['Browse Properties' => '/properties', 'Get Suggestions' => '/buyer/suggestions', 'Create Account' => '/register', 'Sign In' => '/login'] as $label => $href)
                    <li>
                        <a href="{{ url($href) }}"
                           style="font-size:0.875rem; font-weight:300; color:rgba(255,255,255,0.5); text-decoration:none; transition:color 0.2s ease;"
                           onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Sellers --}}
            <div>
                <h4 style="font-size:0.68rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e;
                           font-weight:600; margin-bottom:1.25rem;">For Sellers</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['List Property' => '/seller/properties/create', 'My Listings' => '/seller/properties', 'Seller Dashboard' => '/seller/dashboard'] as $label => $href)
                    <li>
                        <a href="{{ url($href) }}"
                           style="font-size:0.875rem; font-weight:300; color:rgba(255,255,255,0.5); text-decoration:none; transition:color 0.2s ease;"
                           onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 style="font-size:0.68rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e;
                           font-weight:600; margin-bottom:1.25rem;">Company</h4>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach(['About Us' => '#', 'Contact' => '#', 'Privacy Policy' => '#', 'Terms of Use' => '#'] as $label => $href)
                    <li>
                        <a href="{{ url($href) }}"
                           style="font-size:0.875rem; font-weight:300; color:rgba(255,255,255,0.5); text-decoration:none; transition:color 0.2s ease;"
                           onmouseover="this.style.color='#c9a96e'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="border-top:1px solid rgba(255,255,255,0.07); padding:1.75rem 0;
                    display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem;">
            <p style="font-size:0.78rem; font-weight:300; color:rgba(255,255,255,0.3); margin:0;">
                © {{ date('Y') }} RealEstate Suggester. All rights reserved.
            </p>
            <p style="font-size:0.78rem; font-weight:300; color:rgba(255,255,255,0.25); margin:0;">
                Crafted with care for modern real estate
            </p>
        </div>

    </div>
</footer>