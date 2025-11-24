<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-xl font-semibold text-slate-900">Masuk ke Sistem</h1>
        <p class="mt-1 text-sm text-slate-500">
            Gunakan akun <span class="font-semibold">dokter</span> atau <span class="font-semibold">apoteker</span>.
        </p>
    </div>

    {{-- Status dari Breeze (misal: "Password reset link dikirim") --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Kata sandi --}}
        <div>
            <x-input-label for="password" value="Kata sandi" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Ingat saya + lupa password --}}
        <div class="flex items-center justify-between mt-2">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-xs text-gray-600">Ingat saya</span>
            </label>

            {{-- @if (Route::has('password.request'))
                <a class="underline text-xs text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif --}}
        </div>

        {{-- Tombol --}}
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('register'))
                <a class="underline text-xs text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                    Belum punya akun?
                </a>
            @endif

            <x-primary-button class="ms-3">
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
