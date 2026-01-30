<nav class="bg-white border-b px-6 py-4 flex justify-between">

    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
        RealEstate Admin
    </a>

    @auth('admin')
        <div class="flex items-center space-x-4">
            <span>{{ auth('admin')->user()->email }}</span>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-red-600">Logout</button>
            </form>
        </div>
    @endauth
</nav>
