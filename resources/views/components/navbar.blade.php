<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
<nav x-data="{ open: false }" id="mainNav"
    class="nav-root sticky top-0 z-50 border-b border-gray-100 transition-all duration-300" style="background:#faf7f2;">

    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12">
        <div class="flex items-center justify-between h-16">

            {{-- ── BRAND ── --}}
            <div class="flex items-center gap-6 lg:gap-10">
                <a href="{{ route('dashboard') }}" class="nav-brand">
                    Real<span>Estate</span>
                </a>

                {{-- ── DESKTOP LINKS ── --}}
                @auth
                    @php $user = auth()->user(); @endphp
                    <nav class="hidden md:flex items-center">

                        @if($user->isBuyer())
                            <div class="nav-divider"></div>
                            <a href="{{ route('buyer.dashboard') }}"
                                class="nav-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('buyer.properties') }}"
                                class="nav-link {{ request()->routeIs('buyer.properties*') ? 'active' : '' }}">
                                Browse
                            </a>
                            <a href="{{ route('buyer.suggestions') }}"
                                class="nav-link {{ request()->routeIs('buyer.suggestions') ? 'active' : '' }}">
                                Suggestions
                            </a>
                        @endif

                        @if($user->isSeller())
                            <div class="nav-divider"></div>
                            <a href="{{ route('seller.dashboard') }}"
                                class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('seller.properties.create') }}"
                                class="nav-link {{ request()->routeIs('seller.properties.create') ? 'active' : '' }}">
                                Add Property
                            </a>
                            <a href="{{ route('seller.properties.index') }}"
                                class="nav-link {{ request()->routeIs('seller.properties.index') ? 'active' : '' }}">
                                My Listings
                            </a>
                        @endif

                        @if($user->isAdmin())
                            <div class="nav-divider"></div>
                            <a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                Admin
                            </a>
                        @endif

                    </nav>
                @else
                    {{-- Guest links --}}
                    <nav class="hidden md:flex items-center">
                        <div class="nav-divider"></div>
                        <a href="{{ url('/properties') }}"
                            class="nav-link {{ request()->is('properties*') ? 'active' : '' }}">
                            Properties
                        </a>
                    </nav>
                @endauth
            </div>

            {{-- ── RIGHT: AUTH ACTIONS ── --}}
            <div class="hidden md:flex items-center gap-3">

                @auth
                    @php $user = auth()->user(); @endphp
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="user-trigger">
                                <span class="user-avatar">
                                    
                                    {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 2)) }}

                                </span>
                                <span class="user-trigger-label">
                                    {{ Auth::user()->name ?? Str::before(Auth::user()->email, '@') }}

                                </span>
                                <svg class="user-trigger-chevron" xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="nav-dropdown-panel">

                                {{-- Head: email + role badge --}}
                                <div class="dropdown-head">
                                    <p class="dropdown-email">{{ Auth::user()->email }}</p>
                                    <span class="dropdown-badge">
                                        @if($user->isAdmin()) Admin
                                        @elseif($user->isSeller()) Seller
                                        @else Buyer
                                        @endif
                                    </span>
                                </div>

                                <div style="padding:0.375rem 0;">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        My Profile
                                    </a>

                                    {{-- Role-specific shortcut --}}
                                    @if(auth()->user()->isBuyer())
                                        <a href="{{ route('buyer.suggestions') }}" class="dropdown-item">
                                            My Suggestions
                                        </a>
                                    @endif

                                    @if(auth()->user()->isSeller())
                                        <a href="{{ route('seller.properties.index') }}" class="dropdown-item">
                                            My Listings
                                        </a>
                                    @endif

                                    <div class="dropdown-sep"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="color:#b04040;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                                                viewBox="0 0 24 24" stroke="#b04040" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </x-slot>
                    </x-dropdown>

                @else
                    {{-- Guest: login + register --}}
                    <a href="{{ route('login') }}" style="font-size:0.78rem; font-weight:600; letter-spacing:0.07em; text-transform:uppercase;
                                          color:#4a4038; text-decoration:none; padding:0.4rem 0; border-bottom:1.5px solid transparent;
                                          transition:color 0.2s ease, border-color 0.2s ease;"
                        onmouseover="this.style.color='#c9a96e'; this.style.borderColor='#c9a96e'"
                        onmouseout="this.style.color='#4a4038'; this.style.borderColor='transparent'">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" style="font-size:0.78rem; font-weight:600; letter-spacing:0.07em; text-transform:uppercase;
                                          padding:0.5rem 1.25rem; background:#c9a96e; color:#0f0f0f;
                                          border-radius:3px; text-decoration:none; transition:background 0.2s ease;"
                        onmouseover="this.style.background='#b5924f'" onmouseout="this.style.background='#c9a96e'">
                        Get Started
                    </a>
                @endauth

            </div>

            {{-- ── HAMBURGER ── --}}
            <button @click="open = !open" class="hamburger-btn md:hidden" aria-label="Toggle menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    {{-- ── MOBILE MENU ── --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden mobile-panel">

        @auth
            @php $user = auth()->user(); @endphp

            @if($user->isBuyer())
                <p class="mobile-section-label">Buyer</p>
                <a href="{{ route('buyer.dashboard') }}"
                    class="mobile-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('buyer.properties') }}"
                    class="mobile-link {{ request()->routeIs('buyer.properties*') ? 'active' : '' }}">
                    <span class="dot"></span> Browse Properties
                </a>
                <a href="{{ route('buyer.suggestions') }}"
                    class="mobile-link {{ request()->routeIs('buyer.suggestions') ? 'active' : '' }}">
                    <span class="dot"></span> Suggestions
                </a>
            @endif

            @if($user->isSeller())
                <p class="mobile-section-label">Seller</p>
                <a href="{{ route('seller.dashboard') }}"
                    class="mobile-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('seller.properties.create') }}"
                    class="mobile-link {{ request()->routeIs('seller.properties.create') ? 'active' : '' }}">
                    <span class="dot"></span> Add Property
                </a>
                <a href="{{ route('seller.properties.index') }}"
                    class="mobile-link {{ request()->routeIs('seller.properties.index') ? 'active' : '' }}">
                    <span class="dot"></span> My Listings
                </a>
            @endif

            @if($user->isAdmin())
                <p class="mobile-section-label">Admin</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="mobile-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <span class="dot"></span> Admin Dashboard
                </a>
            @endif

            <div class="mobile-footer">
                <div class="mobile-user-info">
                    <span class="mobile-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 2)) }}
                    </span>
                    <span class="mobile-email-text">{{ Auth::user()->email }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;
                                               color:#b04040; background:none; border:none; cursor:pointer; padding:0.25rem 0;">
                        Sign Out
                    </button>
                </form>
            </div>

        @else
        {{-- Guest mobile links --}}
        <div style="padding:1rem 1.25rem; display:flex; flex-direction:column; gap:0.75rem;">
            <a href="{{ route('login') }}" style="font-size:0.8125rem; font-weight:500; color:#3a3028; text-decoration:none; padding:0.6rem 0;
                          border-bottom:1px solid #ede8df;">
                Sign In
            </a>
            <a href="{{ route('register') }}" style="font-size:0.8125rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase;
                          color:#0f0f0f; background:#c9a96e; text-align:center; padding:0.75rem;
                          border-radius:3px; text-decoration:none;">
                Get Started
            </a>
        </div>
        @endguest

    </div>

</nav>

<script>
    (function () {
        const nav = document.getElementById('mainNav');
        if (!nav) return;

        // Check if the first child of <main> contains the hero slider
        function hasHero() {
            const main = document.querySelector('main');
            return main && main.firstElementChild && main.firstElementChild.querySelector('#heroSlider');
        }

        const heroMode = hasHero();

        function update() {
            const scrolled = window.scrollY > 60;
            nav.classList.toggle('scrolled', scrolled || !heroMode);
            nav.classList.toggle('hero-mode', heroMode && !scrolled);
        }

        if (heroMode) {
            window.addEventListener('scroll', update, { passive: true });
        }
        update();
    })();
</script>