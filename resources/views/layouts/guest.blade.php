<!doctype html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Sistem Resep RSAM' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full antialiased">
    <div class="min-h-screen flex">
        {{-- Panel kiri --}}
        <div class="hidden lg:flex flex-1 bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 text-white">
            <div class="max-w-md m-auto px-10">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="h-10 w-10 rounded-2xl bg-white/10 border border-white/30 flex items-center justify-center">
                        <span class="text-xs font-bold tracking-[0.16em] uppercase">RSAM</span>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">Sistem Resep &amp; Penjualan Obat</div>
                        <div class="text-xs text-emerald-100">Mini Project Programmer RSAM 2025</div>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold mb-3">Selamat datang 👋</h2>
                <p class="text-sm text-emerald-100 leading-relaxed">
                    Aplikasi internal untuk dokter dan apoteker dalam mengelola resep dan penjualan obat
                    secara terintegrasi dan terdokumentasi dengan baik.
                </p>
            </div>
        </div>

        {{-- Panel kanan (form) --}}
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
