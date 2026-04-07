<nav x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 10 })"
    :class="scrolled ? 'shadow-md bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm' : 'bg-white dark:bg-gray-900'"
    class="sticky top-0 z-50 border-b border-gray-100 dark:border-gray-800 transition-all duration-300">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .nav-root {
            font-family: 'DM Sans', sans-serif;
        }

        .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.35rem;
            letter-spacing: -0.01em;
            color: #3c3cbb;
        }

        .dark .nav-brand {
            color: #4fa537;
        }

        .nav-brand span {
            color: #b5813a;
        }

        .nav-link {
            position: relative;
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #bcbcce;
            padding: 0.375rem 0;
            margin: 0 0.875rem;
            transition: color 0.2s ease;
            text-decoration: none;
        }

        .dark .nav-link {
            color: #9ca3af;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1.5px;
            background: #b5813a;
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: #6060b2;
        }

        .dark .nav-link:hover {
            color: #f5f0e8;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #b5813a;
        }

        .nav-divider {
            width: 1px;
            height: 1.25rem;
            background: #e5e7eb;
            margin: auto 0.5rem;
        }

        .dark .nav-divider {
            background: #374151;
        }

        /* Dropdown */
        .dropdown-menu {
            min-width: 13rem;
            border: 1px solid #f0ece4;
            border-radius: 0.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .dark .dropdown-menu {
            border-color: #374151;
            box-shadow: 0 8px 30px rgba(0,0,0,0.4);
        }

        .dropdown-header {
            padding: 0.875rem 1rem 0.5rem;
            border-bottom: 1px solid #f0ece4;
        }

        .dark .dropdown-header {
            border-color: #374151;
        }

        .dropdown-email {
            font-size: 0.75rem;
            letter-spacing: 0.01em;
            color: #6b7280;
            font-weight: 400;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 11rem;
        }

        .dropdown-role-badge {
            display: inline-block;
            margin-top: 0.25rem;
            font-size: 0.625rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.125rem 0.5rem;
            border-radius: 2rem;
            background: #fdf3e3;
            color: #b5813a;
        }

        .dark .dropdown-role-badge {
            background: rgba(181,129,58,0.15);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            width: 100%;
            padding: 0.7rem 1rem;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            transition: background 0.15s ease, color 0.15s ease;
            text-decoration: none;
            cursor: pointer;
            background: none;
            border: none;
            text-align: left;
        }

        .dark .dropdown-item {
            color: #d1d5db;
        }

        .dropdown-item:hover {
            background: #fdf8f2;
            color: #b5813a;
        }

        .dark .dropdown-item:hover {
            background: rgba(181,129,58,0.08);
            color: #d4a85e;
        }

        .dropdown-item svg {
            opacity: 0.6;
            flex-shrink: 0;
        }

        .dropdown-item:hover svg {
            opacity: 1;
        }

        .dropdown-separator {
            height: 1px;
            background: #f3f4f6;
            margin: 0.25rem 0;
        }

        .dark .dropdown-separator {
            background: #374151;
        }

        /* User trigger button */
        .user-trigger {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.4rem 0.75rem;
            border-radius: 2rem;
            border: 1px solid #e9e4da;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'DM Sans', sans-serif;
        }

        .dark .user-trigger {
            border-color: #374151;
        }

        .user-trigger:hover {
            background: #fdf8f2;
            border-color: #d4a85e;
        }

        .dark .user-trigger:hover {
            background: rgba(181,129,58,0.08);
            border-color: #b5813a;
        }

        .user-avatar {
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #c4973f, #8a6015);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            flex-shrink: 0;
        }

        .user-trigger-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            max-width: 9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dark .user-trigger-label {
            color: #d1d5db;
        }

        .user-trigger-chevron {
            color: #9ca3af;
            transition: transform 0.2s ease;
        }

        /* Mobile */
        .mobile-menu {
            border-top: 1px solid #f0ece4;
            background: #fdfcfa;
            padding: 0.5rem 0 1rem;
        }

        .dark .mobile-menu {
            border-color: #374151;
            background: #111827;
        }

        .mobile-section-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #b5813a;
            padding: 0.75rem 1.25rem 0.375rem;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.7rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            transition: color 0.15s ease, background 0.15s ease;
        }

        .dark .mobile-nav-link {
            color: #d1d5db;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            color: #b5813a;
            background: #fdf8f2;
        }

        .dark .mobile-nav-link:hover,
        .dark .mobile-nav-link.active {
            background: rgba(181,129,58,0.08);
        }

        .mobile-nav-link .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.5;
        }

        .mobile-footer {
            margin: 0.5rem 1rem 0;
            padding-top: 0.75rem;
            border-top: 1px solid #f0ece4;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dark .mobile-footer {
            border-color: #374151;
        }

        .mobile-email {
            font-size: 0.75rem;
            color: #9ca3af;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 11rem;
        }
    </style>

    <div class="nav-root max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
        <div class="flex justify-between items-center h-16">

            {{-- ── BRAND ── --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="nav-brand shrink-0">
                    Real<span>Estate</span>
                </a>

                {{-- ── DESKTOP NAV ── --}}
                @auth
                    @php $user = auth()->user(); @endphp

                    <nav class="hidden sm:flex items-center">

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
                @endauth
            </div>

            {{-- ── RIGHT: USER DROPDOWN ── --}}
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="user-trigger">
                                <span class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 2)) }}
                                </span>
                                <span class="user-trigger-label">
                                    {{ Auth::user()->name ?? Auth::user()->email }}
                                </span>
                                <svg class="user-trigger-chevron h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                             a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4
                                             a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="dropdown-menu bg-white">
                                {{-- Header --}}
                                <div class="dropdown-header">
                                    <p class="dropdown-email">{{ Auth::user()->email }}</p>
                                    <span class="dropdown-role-badge">
                                        @if($user->isAdmin()) Admin
                                        @elseif($user->isSeller()) Seller
                                        @else Buyer
                                        @endif
                                    </span>
                                </div>

                                {{-- Items --}}
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        My Profile
                                    </a>
                                    @if($user->isBuyer())
                                        <a href="{{ route('buyer.favorites') }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                            My Saved(fav) properties
                                        </a>
                                        <a href="{{ route('buyer.enquiries.index') }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            My Enquiries
                                        </a>

                                    @endif
                                    @if($user->isSeller())
                                        <a href="{{ route('seller.enquiries.index') }}" class="dropdown-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h
                                            w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0017.07 7H21a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            </svg>
                                            See enquiries
                                        </a>
                                    @endif
                                    <div class="dropdown-separator"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-full" style="color:#dc2626">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            {{-- ── HAMBURGER ── --}}
            <button @click="open = !open"
                    class="-me-1 sm:hidden p-2 rounded-md text-gray-400
                           hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800
                           transition-colors focus:outline-none">
                <span class="sr-only">Toggle menu</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{ 'hidden': !open, 'inline-flex': open }"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ── MOBILE MENU ── --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden mobile-menu">
        @auth
            @php $user = auth()->user(); @endphp

            @if($user->isBuyer())
                <p class="mobile-section-label">Buyer</p>
                <a href="{{ route('buyer.dashboard') }}"
                   class="mobile-nav-link {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('buyer.properties') }}"
                   class="mobile-nav-link {{ request()->routeIs('buyer.properties*') ? 'active' : '' }}">
                    <span class="dot"></span> Browse Properties
                </a>
                <a href="{{ route('buyer.suggestions') }}"
                   class="mobile-nav-link {{ request()->routeIs('buyer.suggestions') ? 'active' : '' }}">
                    <span class="dot"></span> Suggestions
                </a>
            @endif

            @if($user->isSeller())
                <p class="mobile-section-label">Seller</p>
                <a href="{{ route('seller.dashboard') }}"
                   class="mobile-nav-link {{ request()->routeIs('seller.*') ? 'active' : '' }}">
                    <span class="dot"></span> Dashboard
                </a>
                <a href="{{ route('seller.properties.create') }}"
                   class="mobile-nav-link {{ request()->routeIs('seller.properties.create') ? 'active' : '' }}">
                    <span class="dot"></span> Add Property
                </a>
                <a href="{{ route('seller.properties.index') }}"
                   class="mobile-nav-link {{ request()->routeIs('seller.properties.index') ? 'active' : '' }}">
                    <span class="dot"></span> My Listings
                </a>
            @endif

            @if($user->isAdmin())
                <p class="mobile-section-label">Admin</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="mobile-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <span class="dot"></span> Admin Dashboard
                </a>
            @endif

            <div class="mobile-footer">
                <span class="mobile-email">{{ Auth::user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors tracking-wide uppercase">
                        Sign Out
                    </button>
                </form>
            </div>
        @endauth
    </div>
</nav>