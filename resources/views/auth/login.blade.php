<x-guest-layout>
    @env('local')
        <div class="mb-6">
            <p class="mb-2 text-[11px] text-slate-500 text-center sm:text-left">
                Contoh akun (jalankan <span class="font-semibold">php artisan db:seed</span>). atau bisa melakukan register.
            </p>

            <div class="grid gap-3 sm:grid-cols-2">
                {{-- Card Dokter --}}
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 px-4 py-3 text-xs text-emerald-900">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-600 text-[11px] font-semibold text-white">
                                DR
                            </span>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-700">
                                    Dokter
                                </p>
                            </div>
                        </div>
                        <span
                            class="rounded-full bg-white/70 px-2 py-0.5 text-[10px] font-medium text-emerald-700 border border-emerald-100">
                            Dev
                        </span>
                    </div>

                    <div class="mt-2 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-emerald-800/80">Email</span>
                            <span class="font-mono text-[11px] font-medium">
                                doctor@example.com
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-emerald-800/80">Password</span>
                            <span class="font-mono text-[11px] font-medium">
                                password
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Card Apoteker --}}
                <div class="rounded-2xl border border-sky-200 bg-sky-50/80 px-4 py-3 text-xs text-sky-900">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-600 text-[11px] font-semibold text-white">
                                AP
                            </span>
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-700">
                                    Apoteker
                                </p>
                            </div>
                        </div>
                        <span
                            class="rounded-full bg-white/70 px-2 py-0.5 text-[10px] font-medium text-sky-700 border border-sky-100">
                            Dev
                        </span>
                    </div>

                    <div class="mt-2 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-sky-800/80">Email</span>
                            <span class="font-mono text-[11px] font-medium">
                                pharmacist@example.com
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-sky-800/80">Password</span>
                            <span class="font-mono text-[11px] font-medium">
                                password
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endenv

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
