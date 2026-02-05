@php
    function activeClass($condition)
    {
        return $condition
            ? 'text-blue-600 font-semibold border-b-2 border-blue-600'
            : 'text-gray-700';
    }
@endphp

<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-6">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
            RealEstate
        </a>

        {{-- MENU --}}
        <div class="hidden md:flex items-center gap-8">

            {{-- HOME --}}
            <a href="{{ route('home') }}" class="font-medium transition hover:text-blue-600
               {{ activeClass(request()->routeIs('home')) }}">
                Home
            </a>

            {{-- BUY --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="font-medium transition hover:text-blue-600
                    {{ activeClass(
    request()->routeIs('properties.list')
    && request('purpose') === 'buy'
) }}">
                    Buy
                </button>

                <div x-show="open" x-transition x-cloak
                    class="absolute left-0 mt-3 w-44 bg-white border rounded-lg shadow-lg z-40">
                    <a href="{{ route('properties.list', ['purpose' => 'buy', 'type' => 'flat']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">Flat</a>
                    <a href="{{ route('properties.list', ['purpose' => 'buy', 'type' => 'house']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">House</a>
                    <a href="{{ route('properties.list', ['purpose' => 'buy', 'type' => 'land']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">Land</a>
                </div>
            </div>

            {{-- SELL --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="font-medium transition hover:text-blue-600
                    {{ activeClass(
    request()->routeIs('properties.list')
    && request('purpose') === 'sell'
) }}">
                    Sell
                </button>

                <div x-show="open" x-transition x-cloak
                    class="absolute left-0 mt-3 w-44 bg-white border rounded-lg shadow-lg z-40">
                    <a href="{{ route('properties.list', ['purpose' => 'sell', 'type' => 'flat']) }}"
                        class="block px-4 py-2 hover:bg-gray-100"></a>
                    <a href="{{ route('properties.list', ['purpose' => 'sell', 'type' => 'house']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">House</a>
                    <a href="{{ route('properties.list', ['purpose' => 'sell', 'type' => 'land']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">Land</a>
                </div>
            </div>

            {{-- PROPERTIES --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false" class="font-medium transition hover:text-blue-600
                    {{ activeClass(
    request()->routeIs('properties.list')
    && !request('purpose')
) }}">
                    Properties
                </button>

                <div x-show="open" x-transition x-cloak
                    class="absolute left-0 mt-3 w-48 bg-white border rounded-lg shadow-lg z-40">
                    <a href="{{ route('properties.list', ['category' => 'residential']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">Residential</a>
                    <a href="{{ route('properties.list', ['category' => 'commercial']) }}"
                        class="block px-4 py-2 hover:bg-gray-100">Commercial</a>
                </div>
            </div>
        </div>

        {{-- SEARCH + AUTH --}}
        <div class="flex items-center gap-4">

            {{-- SEARCH --}}
            <div x-data="{ open:false }" class="relative hidden md:flex">
                <button @click="open = !open" class="text-xl text-gray-700 hover:text-blue-600">
                    🔍
                </button>

                <form x-show="open" x-transition @click.outside="open=false" method="GET"
                    action="{{ route('properties.list') }}"
                    class="absolute right-0 top-full mt-2 flex gap-2 bg-gray-100 px-3 py-2 rounded-lg w-[360px] shadow-lg z-50">

                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search title or location"
                        class="flex-1 bg-transparent text-sm focus:outline-none">

                    <select name="type" class="bg-transparent text-sm focus:outline-none">
                        <option value="">Any</option>
                        <option value="flat">Flat</option>
                        <option value="house">House</option>
                        <option value="land">Land</option>
                    </select>

                    <button class="text-blue-600">🔍</button>
                </form>
            </div>

            {{-- AUTH --}}
            <div class="hidden md:flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="font-medium hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Register
                    </a>
                @endguest

                @auth
                    <span class="font-medium text-gray-700">
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @endauth
            </div>

            {{-- MOBILE SEARCH --}}
            <a href="{{ route('properties.list') }}" class="md:hidden text-xl text-gray-700">
                🔍
            </a>
        </div>
    </div>
</nav>