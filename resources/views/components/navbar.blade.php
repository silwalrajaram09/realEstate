@php
    if (!function_exists('activeClass')) {
        function activeClass($condition, $type = 'default')
        {
            $classes = [
                'default' => $condition
                    ? 'text-blue-600 font-semibold border-b-2 border-blue-600'
                    : 'text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400',

                'mobile' => $condition
                    ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600'
                    : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800'
            ];

            return $classes[$type];
        }
    }
@endphp

<!-- NAVBAR COMPONENT -->
<nav x-data="navbar()" x-init="init" x-cloak :class="{
        '-translate-y-full': !visible && !mobileOpen,
        'shadow-lg backdrop-blur-md bg-white/90 dark:bg-gray-900/90': scrolled,
     }" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 transform bg-white dark:bg-gray-900">

    <!-- PROGRESS BAR (optional) -->
    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-gray-200 dark:bg-gray-700">
        <div x-bind:style="'width: ' + scrollProgress + '%'" class="h-full bg-blue-600 transition-all duration-150">
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- LEFT SECTION: LOGO + MOBILE MENU BUTTON -->
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button @click="mobileOpen = !mobileOpen" type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden transition-colors"
                    aria-controls="mobile-menu" :aria-expanded="mobileOpen">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg x-show="!mobileOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icon when menu is open -->
                    <svg x-show="mobileOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- LOGO -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div
                        class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">RE</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white hidden sm:block">RealEstate</span>
                </a>
            </div>

            <!-- DESKTOP MENU -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200 {{ activeClass(request()->routeIs('home')) }}">
                    Home
                </a>

                <a href="{{ route('properties.list', ['purpose' => 'buy']) }}"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200 {{ activeClass(request()->routeIs('properties.list') && request('purpose') === 'buy') }}">
                    Buy
                </a>

                <a href="{{ route('properties.list', ['purpose' => 'sell']) }}"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200 {{ activeClass(request()->routeIs('properties.list') && request('purpose') === 'sell') }}">
                    Sell
                </a>

                <a href="{{ route('properties.list') }}"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium transition-colors duration-200 {{ activeClass(request()->routeIs('properties.list') && !request('purpose')) }}">
                    Properties
                </a>
            </div>

            <!-- RIGHT SECTION: SEARCH + AUTH + THEME TOGGLE -->
            <div class="flex items-center gap-2 sm:gap-4">

                <!-- SEARCH DROPDOWN (DESKTOP) -->
                <div x-data="{ open: false }" @keydown.escape="open = false" class="relative hidden md:block">

                    <button @click="open = !open" type="button"
                        class="p-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                        :aria-expanded="open" aria-label="Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Search dropdown panel -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1" @click.outside="open = false"
                        class="absolute right-0 top-full mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 overflow-hidden z-50">

                        <form method="GET" action="{{ route('properties.list') }}" class="p-4">
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Search properties..."
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <select name="type"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Property Type</option>
                                    <option value="flat" {{ request('type') == 'flat' ? 'selected' : '' }}>Flat</option>
                                    <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>House
                                    </option>
                                    <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>Land</option>
                                </select>

                                <select name="bedrooms"
                                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Bedrooms</option>
                                    <option value="1">1+</option>
                                    <option value="2">2+</option>
                                    <option value="3">3+</option>
                                    <option value="4">4+</option>
                                </select>
                            </div>

                            <div class="mt-3 flex gap-2">
                                <button type="submit"
                                    class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Search
                                </button>
                                <button type="button" @click="open = false"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MOBILE SEARCH ICON -->
                <a href="{{ route('properties.list') }}"
                    class="md:hidden p-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </a>

                <!-- DARK MODE TOGGLE -->
                <!-- <button @click="toggleDarkMode"
                    class="p-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors"
                    aria-label="Toggle dark mode">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707" />
                    </svg>
                </button> -->

                <!-- AUTH SECTION -->
                <div class="hidden md:flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Register
                        </a>
                    @endguest

                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User dropdown -->
                            <div x-show="open" @click.outside="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-1 z-50">

                                <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Dashboard
                                </a>

                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Profile
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden absolute top-16 inset-x-0 bg-white dark:bg-gray-900 shadow-lg border-t border-gray-200 dark:border-gray-800"
        id="mobile-menu">

        <div class="px-4 py-2 space-y-1">
            <a href="{{ route('home') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ activeClass(request()->routeIs('home'), 'mobile') }}">
                Home
            </a>

            <a href="{{ route('properties.list', ['purpose' => 'buy']) }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ activeClass(request()->routeIs('properties.list') && request('purpose') === 'buy', 'mobile') }}">
                Buy
            </a>

            <a href="{{ route('properties.list', ['purpose' => 'sell']) }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ activeClass(request()->routeIs('properties.list') && request('purpose') === 'sell', 'mobile') }}">
                Sell
            </a>

            <a href="{{ route('properties.list') }}"
                class="block px-3 py-2 rounded-md text-base font-medium {{ activeClass(request()->routeIs('properties.list') && !request('purpose'), 'mobile') }}">
                Properties
            </a>
        </div>

        <!-- Mobile auth section -->
        <div class="border-t border-gray-200 dark:border-gray-800 px-4 py-4">
            @guest
                <div class="space-y-2">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Register
                    </a>
                </div>
            @else
            <div class="space-y-3">
                <div class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('profile') }}"
                        class="block text-gray-700 dark:text-gray-300 hover:text-blue-600">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>

<!-- PAGE CONTENT WRAPPER -->
<main class="pt-16">
    {{ $slot ?? '' }}
</main>

<script>
    function navbar() {
        return {
            visible: true,
            scrolled: false,
            mobileOpen: false,
            darkMode: false,
            lastScroll: 0,
            scrollProgress: 0,

            init() {
                // Check for saved dark mode preference
                this.darkMode = localStorage.getItem('darkMode') === 'true' ||
                    (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);

                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                }

                // Throttled scroll handler
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        window.requestAnimationFrame(() => {
                            this.handleScroll();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
            },

            handleScroll() {
                const currentScroll = window.scrollY;
                this.scrolled = currentScroll > 20;
                this.visible = currentScroll < this.lastScroll || currentScroll < 100;
                this.lastScroll = currentScroll;

                // Calculate scroll progress
                const winHeight = document.documentElement.scrollHeight - window.innerHeight;
                this.scrollProgress = (currentScroll / winHeight) * 100;
            },

            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                localStorage.setItem('darkMode', this.darkMode);
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>