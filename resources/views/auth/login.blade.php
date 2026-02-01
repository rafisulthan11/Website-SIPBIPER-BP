<x-guest-layout>
    <!-- Judul Form -->
    <h1 class="text-lg sm:text-xl font-extrabold text-center text-gray-900 mb-8 whitespace-nowrap">
        {{ __('Masuk untuk memulai sesi Anda') }}
    </h1>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="sr-only" />
            <x-text-input
                id="email"
                class="block mt-1 w-full placeholder:text-gray-400"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Email"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="sr-only" />
            <x-text-input
                id="password"
                class="block mt-1 w-full placeholder:text-gray-400"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Kata Sandi"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember + Submit Row -->
        <div class="mt-2 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center select-none">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-base font-semibold text-gray-800">{{ __('Ingat Saya') }}</span>
            </label>

            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-700 hover:bg-gray-800 text-white text-sm font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-600">
                <!-- Icon: Heroicons outline arrow-right-on-rectangle -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3" />
                </svg>
                {{ __('Masuk') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <div class="pt-2">
                <a class="text-base font-semibold text-gray-500 hover:text-gray-700 focus:outline-none" href="{{ route('password.request') }}">
                    {{ __('Lupa Kata Sandi') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
