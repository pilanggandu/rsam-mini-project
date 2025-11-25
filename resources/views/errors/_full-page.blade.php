{{-- resources/views/errors/_full-page.blade.php --}}
@props([
    'code' => 500,
    'title' => 'Terjadi kesalahan',
    'message' => 'Terjadi kesalahan di sistem. Silakan coba lagi beberapa saat lagi.',
])

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $code }} — {{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-50 antialiased">
    {{-- Background dekorasi --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        {{-- blob merah --}}
        <div class="absolute -top-40 -left-32 h-72 w-72 rounded-full bg-primary-500/25 blur-3xl"></div>
        {{-- blob teal --}}
        <div class="absolute -bottom-32 -right-20 h-72 w-72 rounded-full bg-teal-500/25 blur-3xl"></div>
        {{-- radial halus --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(148,163,184,0.18),_transparent_60%)]"></div>
    </div>

    <main class="min-h-screen px-4 flex items-center justify-center">
        <div class="w-full max-w-xl space-y-6">

            {{-- Chip kecil di atas card --}}
            <div
                class="inline-flex items-center gap-2 rounded-full border border-slate-800/70 bg-slate-950/70 px-3 py-1 text-xs">
                <span
                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-teal-500/15 font-semibold text-[11px] text-teal-300">
                    {{ $code }}
                </span>
                <span class="uppercase tracking-[0.16em] text-[10px] text-slate-400">
                    Error
                    {{ $code >= 400 && $code < 500 ? 'Client' : ($code >= 500 ? 'Server' : 'Unexpected') }}
                </span>
            </div>

            {{-- Card utama --}}
            <section
                class="relative overflow-hidden rounded-3xl border border-slate-800/70 bg-slate-900/80 shadow-2xl shadow-black/60">
                {{-- Garis highlight di atas: merah → teal --}}
                <div
                    class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-primary-400/40 via-teal-400/80 to-primary-400/40">
                </div>

                {{-- Accent strip kiri di desktop: merah + teal --}}
                <div
                    class="hidden md:block absolute inset-y-0 left-0 w-px bg-gradient-to-b from-primary-500/60 via-teal-400/70 to-transparent">
                </div>

                <div class="relative p-6 md:p-8">
                    {{-- Header konten --}}
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-900/80 ring-1 ring-slate-700/70">
                            {{-- Icon dengan gradient stroke via 2 warna teks --}}
                            <svg class="h-5 w-5 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 8v5" />
                                <path d="M12 16h.01" />
                            </svg>
                        </div>

                        <div class="space-y-2">
                            <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                                {{ $title }}
                            </h1>
                            <p class="text-sm text-slate-300 leading-relaxed">
                                {{ $message }}
                            </p>

                            @isset($slot)
                                <div class="mt-1 text-xs text-slate-400 leading-relaxed">
                                    {{ $slot }}
                                </div>
                            @endisset
                        </div>
                    </div>

                    {{-- Saran langkah yang bisa dilakukan --}}
                    <div class="mt-5 border-t border-slate-800 pt-4">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500 mb-2">
                            Yang bisa Anda coba
                        </p>
                        <ul class="space-y-1.5 text-xs text-slate-300">
                            <li class="flex gap-2">
                                <span class="mt-[3px] h-1.5 w-1.5 rounded-full bg-emerald-400/80"></span>
                                <span>Muat ulang halaman untuk memastikan ini bukan gangguan sementara.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-[3px] h-1.5 w-1.5 rounded-full bg-amber-400/90"></span>
                                <span>Pastikan koneksi internet stabil.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-[3px] h-1.5 w-1.5 rounded-full bg-teal-400/90"></span>
                                <span>Jika error berulang, screenshoot halaman ini dan kirimkan ke admin sistem.</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="mt-6 flex flex-wrap items-center gap-2">

                        <button type="button"
                            onclick="window.history.length > 1 ? window.history.back() : window.location.assign('{{ url('/') }}')"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium
                                   border border-slate-700/80 bg-slate-900/70 text-slate-200 hover:bg-slate-800/90">
                            Kembali
                        </button>

                        <button type="button" onclick="window.location.reload()"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium
                                   border border-slate-700/80 bg-slate-900/70 text-slate-200 hover:bg-slate-800/90">
                            Muat Ulang
                        </button>
                    </div>

                    {{-- Footer kecil --}}
                    <div
                        class="mt-6 border-t border-slate-800/80 pt-3 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                        <span>
                            Jika masalah berulang, hubungi admin sistem untuk pengecekan lebih lanjut.
                        </span>
                        <span class="font-mono text-[10px] text-teal-400/80">
                            RSAM Mini Project • {{ $code }}
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
