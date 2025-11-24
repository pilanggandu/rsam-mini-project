<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-xl font-semibold text-slate-900">Buat Akun Baru</h1>
        <p class="mt-1 text-sm text-slate-500">
            Pilih peran sesuai kebutuhan: <span class="font-semibold">dokter</span> atau <span
                class="font-semibold">apoteker</span>.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Role --}}
        <div>
            <x-input-label for="role" value="Daftar Sebagai" />
            <select id="role" name="role"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm
                           focus:border-emerald-500 focus:ring-emerald-500">
                <option value="doctor" {{ old('role') === 'doctor' ? 'selected' : '' }}>Dokter</option>
                <option value="pharmacist" {{ old('role') === 'pharmacist' ? 'selected' : '' }}>Apoteker</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        {{-- Nama --}}
        <div>
            <x-input-label for="name" value="Nama lengkap" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Alamat email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Kata sandi --}}
        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Konfirmasi kata sandi --}}
        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi kata sandi" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-xs text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-3">
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
