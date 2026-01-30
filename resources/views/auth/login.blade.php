<x-guest-layout>

        <div class="w-full max-w-md bg-white/90  shadow-2xl rounded-2xl p-8">

            <!-- Logo / Title -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                    Welcome Back 👋
                </h1>
                <p class="text-sm text-gray-500 mt-2">
                    Login to manage your account
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input
                        id="email"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <span class="ml-2 text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-indigo-600 hover:text-indigo-800 font-medium"
                        >
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold tracking-wide shadow-lg hover:scale-[1.02] transition-all duration-200"
                >
                    Log In →
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center mt-6 text-sm text-gray-600">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:underline">
                    Create one
                </a>
            </div>
        </div>
</x-guest-layout>
