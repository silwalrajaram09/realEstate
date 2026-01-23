<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4 md:gap-0">

        {{-- Logo --}}
        <a href="/" class="text-2xl font-bold text-blue-600">
            RealEstate
        </a>

        {{-- Menu --}}
        <div class="flex flex-col md:flex-row md:items-center md:gap-6 w-full md:w-auto">

            {{-- Home --}}
            <a href="/" class="hover:text-blue-600 font-medium md:mr-4">
                Home
            </a>

            {{-- BUY --}}
            <form action="{{ route('buy.filter') }}" method="GET" class="mb-2 md:mb-0">
                <select name="type"
                        onchange="this.form.submit()"
                        class="border rounded px-3 py-2 text-gray-700 focus:ring focus:ring-blue-300">
                    <option value="">Buy</option>
                    <option value="flat">Flat</option>
                    <option value="house">House</option>
                    <option value="land">Land</option>
                </select>
            </form>

            {{-- SELL --}}
            <form action="{{ route('sell.filter') }}" method="GET" class="mb-2 md:mb-0">
                <select name="type"
                        onchange="this.form.submit()"
                        class="border rounded px-3 py-2 text-gray-700 focus:ring focus:ring-blue-300">
                    <option value="">Sell</option>
                    <option value="flat">Flat</option>
                    <option value="house">House</option>
                    <option value="land">Land</option>
                </select>
            </form>

            {{-- PROPERTIES --}}
            <form action="{{ route('properties.index') }}" method="GET" class="mb-2 md:mb-0">
                <select name="category"
                        onchange="this.form.submit()"
                        class="border rounded px-3 py-2 text-gray-700 focus:ring focus:ring-blue-300">
                    <option value="">Properties</option>
                    <option value="residential">Residential</option>
                    <option value="commercial">Commercial</option>
                </select>
            </form>

        <div class="flex items-center gap-3 mt-2 md:mt-0">
 @guest
        <a href="{{ route('login') }}" class="hover:text-blue-600 font-medium">Login</a>
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</a>
    @endguest

    @auth
        <span class="text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800 ml-2">Logout</button>
        </form>
    @endauth
        </div>
        </div>

        {{-- Auth Buttons --}}

    </div>
</nav>
