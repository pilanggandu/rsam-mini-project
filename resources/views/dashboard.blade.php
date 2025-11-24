{{-- resources/views/dashboard-dokter.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Dokter
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Ringkasan aktivitas resep dan pasien yang Anda tangani.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total resep saya hari ini --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-4">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Resep Saya Hari Ini
                        </div>
                        <div class="text-2xl font-semibold text-slate-900">
                            {{ $total_resep_saya_hari_ini ?? 0 }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            Jumlah resep yang Anda buat pada hari ini.
                        </p>
                    </div>
                </div>

                {{-- Pasien unik bulan ini --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-4">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Pasien Unik Bulan Ini
                        </div>
                        <div class="text-2xl font-semibold text-slate-900">
                            {{ $total_pasien_unik_bulan_ini ?? 0 }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            Total pasien berbeda yang Anda layani di bulan berjalan.
                        </p>
                    </div>
                </div>

                {{-- Resep menunggu apotek --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-4">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Resep Menunggu Apotek
                        </div>
                        <div class="text-2xl font-semibold text-amber-600">
                            {{ $total_resep_menunggu_apotek ?? 0 }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            Resep yang sudah selesai Anda isi dan menunggu diproses apotek.
                        </p>
                    </div>
                </div>

                {{-- Resep draft --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-4">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Resep Draft
                        </div>
                        <div class="text-2xl font-semibold text-rose-600">
                            {{ $total_resep_draft ?? 0 }}
                        </div>
                        <p class="mt-1 text-[11px] text-slate-500">
                            Resep yang belum lengkap / belum dikirim ke apotek.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Dua panel: Resep terbaru & Menunggu apotek --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Resep terbaru saya --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Resep Terbaru Saya
                            </h3>
                            <p class="text-xs text-slate-500">
                                Beberapa resep terakhir yang Anda buat.
                            </p>
                        </div>
                        @if (Route::has('resep.index'))
                            <a href="{{ route('resep.index') }}"
                                class="text-xs text-emerald-600 hover:text-emerald-700">
                                Lihat semua
                            </a>
                        @endif
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($daftar_resep_terbaru ?? [] as $resep)
                            <div class="px-4 py-3 flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $resep->nomor_resep }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Pasien:
                                        <span class="font-medium">
                                            {{ $resep->pasien->nama_pasien ?? '-' }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ optional($resep->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium
                                        @class([
                                            'bg-slate-100 text-slate-700' => $resep->status === 'draft',
                                            'bg-emerald-50 text-emerald-700' => $resep->status === 'completed',
                                            'bg-amber-50 text-amber-700' => $resep->status === 'processed',
                                        ])">
                                        @switch($resep->status)
                                            @case('draft')
                                                Draft
                                            @break

                                            @case('completed')
                                                Selesai
                                            @break

                                            @case('processed')
                                                Sudah diproses apotek
                                            @break

                                            @default
                                                {{ ucfirst($resep->status) }}
                                        @endswitch
                                    </span>

                                    @if (Route::has('resep.show'))
                                        <a href="{{ route('resep.show', $resep) }}"
                                            class="text-[11px] text-emerald-600 hover:text-emerald-700">
                                            Detail
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-slate-500 text-center">
                                    Belum ada data resep.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Resep menunggu apotek --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">
                                    Resep Menunggu Apotek
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Resep yang sudah Anda lengkapi dan belum dibuat penjualan oleh apotek.
                                </p>
                            </div>
                            @if (Route::has('resep.index'))
                                <a href="{{ route('resep.index', ['filter' => 'menunggu-apotek']) }}"
                                    class="text-xs text-emerald-600 hover:text-emerald-700">
                                    Lihat semua
                                </a>
                            @endif
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse($daftar_resep_menunggu_apotek ?? [] as $resep)
                                <div class="px-4 py-3 flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $resep->nomor_resep }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            Pasien:
                                            <span class="font-medium">
                                                {{ $resep->pasien->nama_pasien ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ optional($resep->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium bg-amber-50 text-amber-700">
                                            Menunggu apotek
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-6 text-sm text-slate-500 text-center">
                                    Tidak ada resep yang menunggu apotek.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </x-app-layout>
