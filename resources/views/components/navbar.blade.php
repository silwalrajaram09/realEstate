<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Outfit:wght@300;400;500;600&display=swap');

    .nav-root {
        font-family: 'Outfit', sans-serif;
        -webkit-font-smoothing: antialiased;
    }

    /* Brand */
    .nav-brand {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #0f0f0f;
        text-decoration: none;
        letter-spacing: -0.01em;
        transition: opacity 0.2s ease;
        flex-shrink: 0;
    }
    .nav-brand:hover { opacity: 0.8; }
    .nav-brand span { color: #c9a96e; }

    /* Scrolled state (toggled via JS) */
    nav.scrolled {
        background: rgba(250,247,242,0.96) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 1px 0 #ede8df, 0 4px 20px rgba(0,0,0,0.05);
    }
    nav.hero-mode {
        background: transparent !important;
        border-bottom-color: transparent !important;
    }
    nav.hero-mode .nav-brand { color: #fff; }
    nav.hero-mode .nav-brand span { color: #c9a96e; }
    nav.hero-mode .nav-link { color: rgba(255,255,255,0.75); }
    nav.hero-mode .nav-link:hover { color: #fff; }
    nav.hero-mode .nav-divider { background: rgba(255,255,255,0.15); }
    nav.hero-mode .user-trigger {
        border-color: rgba(255,255,255,0.25);
        background: rgba(255,255,255,0.05);
    }
    nav.hero-mode .user-trigger-label { color: rgba(255,255,255,0.85); }
    nav.hero-mode .user-trigger-chevron { color: rgba(255,255,255,0.5); }
    nav.hero-mode .hamburger-btn { color: rgba(255,255,255,0.8); }

    /* Desktop nav links */
    .nav-link {
        position: relative;
        font-size: 0.78rem;
        font-weight: 500;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #4a4038;
        padding: 0.35rem 0;
        margin: 0 0.875rem;
        text-decoration: none;
        transition: color 0.2s ease;
        white-space: nowrap;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 0;
        height: 1.5px;
        background: #c9a96e;
        transition: width 0.3s ease;
    }
    .nav-link:hover { color: #0f0f0f; }
    .nav-link:hover::after,
    .nav-link.active::after { width: 100%; }
    .nav-link.active { color: #c9a96e; }

    .nav-divider {
        width: 1px;
        height: 1.1rem;
        background: #ddd5c8;
        margin: auto 0.25rem;
        flex-shrink: 0;
    }

    /* User trigger pill */
    .user-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.375rem 0.875rem 0.375rem 0.375rem;
        border: 1px solid #ddd5c8;
        border-radius: 2rem;
        background: transparent;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
        font-family: 'Outfit', sans-serif;
    }
    .user-trigger:hover {
        border-color: #c9a96e;
        background: rgba(201,169,110,0.06);
    }

    .user-avatar {
        width: 1.875rem;
        height: 1.875rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a96e, #9a7340);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        flex-shrink: 0;
    }

    .user-trigger-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #2a2a2a;
        max-width: 9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-trigger-chevron {
        color: #9a8878;
        transition: transform 0.2s ease;
        flex-shrink: 0;
    }

    /* Dropdown panel */
    .nav-dropdown-panel {
        min-width: 14rem;
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 6px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.05);
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
    }

    .dropdown-head {
        padding: 1rem 1.125rem 0.75rem;
        border-bottom: 1px solid #f2ece2;
    }
    .dropdown-email {
        font-size: 0.775rem;
        color: #6b5e52;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 11rem;
    }
    .dropdown-badge {
        display: inline-block;
        margin-top: 0.3rem;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 0.15rem 0.6rem;
        border-radius: 2rem;
        background: rgba(201,169,110,0.12);
        color: #9a7340;
        border: 1px solid rgba(201,169,110,0.3);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        width: 100%;
        padding: 0.65rem 1.125rem;
        font-size: 0.8125rem;
        font-weight: 400;
        color: #3a3028;
        text-decoration: none;
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .dropdown-item:hover { background: #fdf8f2; color: #c9a96e; }
    .dropdown-item svg { opacity: 0.55; flex-shrink: 0; }
    .dropdown-item:hover svg { opacity: 1; }
    .dropdown-sep { height: 1px; background: #f4ede3; margin: 0.25rem 0; }

    /* Mobile menu */
    .mobile-panel {
        background: #faf7f2;
        border-top: 1px solid #ede8df;
    }

    .mobile-section-label {
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #c9a96e;
        padding: 1rem 1.25rem 0.375rem;
    }

    .mobile-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 400;
        color: #3a3028;
        text-decoration: none;
        transition: color 0.15s ease, background 0.15s ease;
    }
    .mobile-link:hover, .mobile-link.active {
        color: #c9a96e;
        background: rgba(201,169,110,0.05);
    }
    .mobile-link .dot {
        width: 4px; height: 4px;
        border-radius: 50%;
        background: #c9a96e;
        opacity: 0.5;
        flex-shrink: 0;
    }
    .mobile-link.active .dot { opacity: 1; }

    .mobile-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.875rem 1.25rem;
        margin-top: 0.25rem;
        border-top: 1px solid #ede8df;
    }
    .mobile-user-info { display: flex; align-items: center; gap: 0.625rem; }
    .mobile-avatar {
        width: 1.75rem; height: 1.75rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #c9a96e, #9a7340);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.6rem; font-weight: 700;
        flex-shrink: 0;
    }
    .mobile-email-text {
        font-size: 0.75rem; color: #8c8070;
        overflow: hidden; text-overflow: ellipsis;
        white-space: nowrap; max-width: 10rem;
    }

    .hamburger-btn {
        padding: 0.5rem;
        border-radius: 4px;
        color: #4a4038;
        background: none;
        border: none;
        cursor: pointer;
        transition: color 0.2s ease, background 0.2s ease;
    }
    .hamburger-btn:hover { background: rgba(201,169,110,0.08); color: #c9a96e; }
</style>

<nav x-data="{ open: false }"
     id="mainNav"
     class="nav-root sticky top-0 z-50 border-b border-gray-100 transition-all duration-300"
     style="background:#faf7f2;">

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
                        <a href="{{ url('/properties') }}" class="nav-link {{ request()->is('properties*') ? 'active' : '' }}">
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
                                <svg class="user-trigger-chevron" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>

                                    {{-- Role-specific shortcut --}}
                                    @if($user->isBuyer())
                                        <a href="{{ route('buyer.suggestions') }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                            </svg>
                                            My Suggestions
                                        </a>
                                    @elseif($user->isSeller())
                                        <a href="{{ route('seller.properties.index') }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            My Listings
                                        </a>
                                    @endif

                                    <div class="dropdown-sep"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="color:#b04040;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#b04040" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                    <a href="{{ route('login') }}"
                       style="font-size:0.78rem; font-weight:600; letter-spacing:0.07em; text-transform:uppercase;
                              color:#4a4038; text-decoration:none; padding:0.4rem 0; border-bottom:1.5px solid transparent;
                              transition:color 0.2s ease, border-color 0.2s ease;"
                       onmouseover="this.style.color='#c9a96e'; this.style.borderColor='#c9a96e'"
                       onmouseout="this.style.color='#4a4038'; this.style.borderColor='transparent'">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       style="font-size:0.78rem; font-weight:600; letter-spacing:0.07em; text-transform:uppercase;
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
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
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
                <a href="{{ route('buyer.dashboard') }}" class="mobile-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('buyer.properties') }}" class="mobile-link {{ request()->routeIs('buyer.properties*') ? 'active' : '' }}">
                    <span class="dot"></span> Browse Properties
                </a>
                <a href="{{ route('buyer.suggestions') }}" class="mobile-link {{ request()->routeIs('buyer.suggestions') ? 'active' : '' }}">
                    <span class="dot"></span> Suggestions
                </a>
            @endif

            @if($user->isSeller())
                <p class="mobile-section-label">Seller</p>
                <a href="{{ route('seller.dashboard') }}" class="mobile-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('seller.properties.create') }}" class="mobile-link {{ request()->routeIs('seller.properties.create') ? 'active' : '' }}">
                    <span class="dot"></span> Add Property
                </a>
                <a href="{{ route('seller.properties.index') }}" class="mobile-link {{ request()->routeIs('seller.properties.index') ? 'active' : '' }}">
                    <span class="dot"></span> My Listings
                </a>
            @endif

            @if($user->isAdmin())
                <p class="mobile-section-label">Admin</p>
                <a href="{{ route('admin.dashboard') }}" class="mobile-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
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
                <a href="{{ route('login') }}"
                   style="font-size:0.8125rem; font-weight:500; color:#3a3028; text-decoration:none; padding:0.6rem 0;
                          border-bottom:1px solid #ede8df;">
                    Sign In
                </a>
                <a href="{{ route('register') }}"
                   style="font-size:0.8125rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase;
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