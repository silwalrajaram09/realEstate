<x-guest-layout>
    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" name="password" type="password" required />
        </div>

        <div class="mt-4">
            <x-primary-button>
                Admin Login
            </x-primary-button>
        </div>

        @if ($errors->any())
            <p class="mt-2 text-red-600">{{ $errors->first() }}</p>
        @endif
    </form>
</x-guest-layout>
