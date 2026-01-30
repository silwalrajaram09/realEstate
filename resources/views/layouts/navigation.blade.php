<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- LEFT -->
            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                       class="text-xl font-bold text-gray-800 dark:text-white">
                        RealEstate
                    </a>
                </div>

                <!-- Desktop Nav -->
                @auth
                    @php $user = auth()->user(); @endphp

                    <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">

                        {{-- BUYER --}}
                        @if($user->isBuyer())
                            <x-nav-link
                                :href="route('buyer.dashboard')"
                                :active="request()->routeIs('buyer.*')">
                                Dashboard
                            </x-nav-link>

                            <x-nav-link
                                :href="route('properties.buy')"
                                :active="request()->routeIs('properties.buy')">
                                Buy Property
                            </x-nav-link>

                            <x-nav-link
                                :href="route('properties.suggestions')"
                                :active="request()->routeIs('properties.suggestions')">
                                Suggestions
                            </x-nav-link>
                        @endif

                        {{-- SELLER --}}
                        @if($user->isSeller())
                            <x-nav-link
                                :href="route('seller.dashboard')"
                                :active="request()->routeIs('seller.dashboard')">
                                Dashboard
                            </x-nav-link>

                            <x-nav-link
                                :href="route('seller.properties.create')"
                                :active="request()->routeIs('seller.properties.create')">
                                Add Property
                            </x-nav-link>

                            <x-nav-link
                                :href="route('seller.properties.index')"
                                :active="request()->routeIs('seller.properties.index')">
                                My Listings
                            </x-nav-link>
                        @endif

                        {{-- ADMIN --}}
                        @if($user->isAdmin())
                            <x-nav-link
                                :href="route('admin.dashboard')"
                                :active="request()->routeIs('admin.*')">
                                Admin Dashboard
                            </x-nav-link>
                        @endif

                    </div>
                @endauth
            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2
                                text-sm font-medium rounded-md
                                text-gray-500 dark:text-gray-400
                                hover:text-gray-700 dark:hover:text-gray-300">

                                {{ Auth::user()->email }}

                                <svg class="ms-1 h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                        a1 1 0 111.414 1.414l-4 4
                                        a1 1 0 01-1.414 0l-4-4
                                        a1 1 0 010-1.414z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        @auth
            @php $user = auth()->user(); @endphp

            <div class="pt-2 pb-3 space-y-1">

                @if($user->isBuyer())
                    <x-responsive-nav-link
                        :href="route('buyer.dashboard')"
                        :active="request()->routeIs('buyer.*')">
                        Buyer Dashboard
                    </x-responsive-nav-link>
                @endif

                @if($user->isSeller())
                    <x-responsive-nav-link
                        :href="route('seller.dashboard')"
                        :active="request()->routeIs('seller.*')">
                        Seller Dashboard
                    </x-responsive-nav-link>
                @endif

                @if($user->isAdmin())
                    <x-responsive-nav-link
                        :href="route('admin.dashboard')"
                        :active="request()->routeIs('admin.*')">
                        Admin Dashboard
                    </x-responsive-nav-link>
                @endif

            </div>
        @endauth
    </div>
</nav>
