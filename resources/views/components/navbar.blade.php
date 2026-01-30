@php
    function navActive($condition) {
        return $condition
            ? 'text-blue-600 font-semibold border-b-2 border-blue-600'
            : 'text-gray-700';
    }
@endphp

<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="text-2xl font-bold text-blue-600">
            RealEstate
        </a>

        {{-- Menu --}}
        <div class="hidden md:flex items-center gap-8">

            {{-- HOME --}}
            <a href="{{ route('home') }}"
               class="font-medium transition hover:text-blue-600
               {{ navActive(request()->routeIs('home')) }}">
                Home
            </a>

            {{-- BUY --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="font-medium transition hover:text-blue-600
                    {{ navActive(request()->routeIs('buy.*')) }}"
                >
                    Buy
                </button>

                <div
                    x-show="open"
                    x-transition
                    x-cloak
                    class="absolute left-0 mt-3 w-44 bg-white border rounded-lg shadow-lg overflow-hidden"
                >
                    <a href="{{ route('buy.filter', ['type'=>'flat']) }}"
                       class="block px-4 py-2 hover:bg-gray-100
                       {{ request()->fullUrlIs('*type=flat*') ? 'bg-gray-100 text-blue-600' : '' }}">
                        Flat
                    </a>

                    <a href="{{ route('buy.filter', ['type'=>'house']) }}"
                       class="block px-4 py-2 hover:bg-gray-100
                       {{ request()->fullUrlIs('*type=house*') ? 'bg-gray-100 text-blue-600' : '' }}">
                        House
                    </a>

                    <a href="{{ route('buy.filter', ['type'=>'land']) }}"
                       class="block px-4 py-2 hover:bg-gray-100
                       {{ request()->fullUrlIs('*type=land*') ? 'bg-gray-100 text-blue-600' : '' }}">
                        Land
                    </a>
                </div>
            </div>

            {{-- SELL --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="font-medium transition hover:text-blue-600
                    {{ navActive(request()->routeIs('sell.*')) }}"
                >
                    Sell
                </button>

                <div
                    x-show="open"
                    x-transition
                    x-cloak
                    class="absolute left-0 mt-3 w-44 bg-white border rounded-lg shadow-lg overflow-hidden"
                >
                    <a href="{{ route('sell.filter', ['type'=>'flat']) }}"
                       class="block px-4 py-2 hover:bg-gray-100">
                        Flat
                    </a>

                    <a href="{{ route('sell.filter', ['type'=>'house']) }}"
                       class="block px-4 py-2 hover:bg-gray-100">
                        House
                    </a>

                    <a href="{{ route('sell.filter', ['type'=>'land']) }}"
                       class="block px-4 py-2 hover:bg-gray-100">
                        Land
                    </a>
                </div>
            </div>

            {{-- PROPERTIES --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="font-medium transition hover:text-blue-600
                    {{ navActive(request()->routeIs('properties.*')) }}"
                >
                    Properties
                </button>

                <div
                    x-show="open"
                    x-transition
                    x-cloak
                    class="absolute left-0 mt-3 w-48 bg-white border rounded-lg shadow-lg overflow-hidden"
                >
                    <a href="{{ route('properties.index', ['category'=>'residential']) }}"
                       class="block px-4 py-2 hover:bg-gray-100">
                        Residential
                    </a>

                    <a href="{{ route('properties.index', ['category'=>'commercial']) }}"
                       class="block px-4 py-2 hover:bg-gray-100">
                        Commercial
                    </a>
                </div>
            </div>
        </div>

        {{-- AUTH --}}
        <div class="hidden md:flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}"
                   class="font-medium hover:text-blue-600">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Register
                </a>
            @endguest

            @auth
                <span class="font-medium text-gray-700">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-600 hover:text-red-800">
                        Logout
                    </button>
                </form>
            @endauth
        </div>

    </div>
</nav>
