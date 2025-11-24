<!doctype html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Resep RSAM</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased text-slate-800">
    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-lg shadow-emerald-500/40">
                        <span class="text-xs font-bold tracking-[0.16em] uppercase text-white">RSAM</span>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Sistem Resep &amp; Penjualan Obat</div>
                        <div class="text-xs text-slate-500">Mini Project Programmer RSAM 2025</div>
                    </div>
                </div>

                <nav class="flex items-center gap-3 text-xs sm:text-sm">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 hover:border-emerald-500 hover:text-emerald-600">
                            Buka Dashboard
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-1.5 hover:border-slate-300">
                                Masuk
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 text-white px-3 py-1.5 rounded-xl shadow-sm hover:bg-emerald-700">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        {{-- Hero section --}}
        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid gap-10 lg:grid-cols-2 items-center">
                    {{-- Kiri: teks utama --}}
                    <div>
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-50 text-emerald-700 text-xs px-3 py-1 mb-4">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Mini Project Programmer RSAM 2025
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900">
                            Sistem Resep &amp; Penjualan Obat
                            <span class="block text-emerald-600 mt-1">untuk Dokter &amp; Apoteker</span>
                        </h1>

                        <p class="mt-4 text-sm sm:text-base text-slate-600 leading-relaxed">
                            Aplikasi internal sederhana untuk membantu dokter dalam membuat resep
                            dan apoteker dalam memproses penjualan obat dengan data yang tercatat rapi,
                            terpusat, dan mudah ditelusuri.
                        </p>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                                    Masuk ke Dashboard
                                </a>
                            @else
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}"
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                                        Masuk sebagai pengguna
                                    </a>
                                @endif

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-emerald-500 hover:text-emerald-600">
                                        Buat akun baru
                                    </a>
                                @endif
                            @endauth
                        </div>

                        {{-- List fitur singkat --}}
                        <dl class="mt-8 grid gap-4 sm:grid-cols-2 text-sm">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 h-6 w-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xs">
                                    ✓
                                </div>
                                <div>
                                    <dt class="font-semibold text-slate-900">Manajemen Resep</dt>
                                    <dd class="text-slate-600 text-xs mt-1">
                                        Dokter dapat membuat, melihat, dan mengelola resep pasien dengan mudah.
                                    </dd>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 h-6 w-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xs">
                                    ✓
                                </div>
                                <div>
                                    <dt class="font-semibold text-slate-900">Penjualan Obat</dt>
                                    <dd class="text-slate-600 text-xs mt-1">
                                        Apoteker memproses resep menjadi transaksi penjualan dan update stok otomatis.
                                    </dd>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 h-6 w-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xs">
                                    ✓
                                </div>
                                <div>
                                    <dt class="font-semibold text-slate-900">Master Obat</dt>
                                    <dd class="text-slate-600 text-xs mt-1">
                                        Data obat tersimpan rapi dengan informasi stok dan harga jual.
                                    </dd>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 h-6 w-6 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-xs">
                                    ✓
                                </div>
                                <div>
                                    <dt class="font-semibold text-slate-900">Hak akses per peran</dt>
                                    <dd class="text-slate-600 text-xs mt-1">
                                        Fitur dibedakan antara dokter dan apoteker untuk menjaga keamanan data.
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    {{-- Kanan: kartu ringkasan dummy --}}
                    <div class="lg:pl-8">
                        <div
                            class="rounded-3xl border border-slate-200 bg-white/80 backdrop-blur shadow-sm p-5 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-slate-500 uppercase">Ringkasan hari ini</div>
                                    <div class="mt-1 text-sm text-slate-900 font-semibold">Contoh data dummy</div>
                                </div>
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700">
                                    Demo
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                                    <div class="text-slate-500 mb-1">Resep hari ini</div>
                                    <div class="text-2xl font-semibold text-slate-900">12</div>
                                    <div class="mt-1 text-[11px] text-emerald-600">8 belum diproses</div>
                                </div>
                                <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                                    <div class="text-slate-500 mb-1">Penjualan hari ini</div>
                                    <div class="text-2xl font-semibold text-slate-900">Rp 3,5 jt</div>
                                    <div class="mt-1 text-[11px] text-slate-500">Data contoh</div>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-slate-200 pt-3 text-[11px] text-slate-500">
                                Setelah login, data di atas akan digantikan dengan data nyata dari sistem.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-200 bg-white">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-[11px] text-slate-500">
                    &copy; {{ date('Y') }} Rumah Sakit Anwar Medika.
                </p>
                <p class="text-[11px] text-slate-400">
                    Tech Stack: Laravel + Breeze · TailwindCSS · Vite
                </p>
            </div>
        </footer>
    </div>
</body>

</html>
