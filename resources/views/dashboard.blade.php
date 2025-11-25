<x-app-layout>
    {{-- Header Bawaan Breeze --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard {{ $roleLabel ?? 'Dashboard' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Hai, {{ $user->name }}.
                @if ($role === 'doctor')
                    Ringkasan aktivitas resep & pasien hari ini.
                @elseif ($role === 'pharmacist')
                    Monitor resep & stok obat di apotek.
                @else
                    Ringkasan aktivitas akun anda.
                @endif
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- =======================
                 DASHBOARD: DOCTOR
                 ======================= --}}
            @if ($role === 'doctor')
                {{-- Stat cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total resep saya hari ini --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Resep saya hari ini
                            </div>
                            <div class="text-2xl font-semibold text-slate-900">
                                {{ $total_resep_saya_hari_ini ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Jumlah resep yang anda buat hari ini.
                            </p>
                        </div>
                    </div>

                    {{-- Pasien unik bulan ini --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Pasien bulan ini
                            </div>
                            <div class="text-2xl font-semibold text-emerald-600">
                                {{ $total_pasien_unik_bulan_ini ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Total pasien yang anda layani bulan ini.
                            </p>
                        </div>
                    </div>

                    {{-- Resep menunggu apotek --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Resep menunggu apotek
                            </div>
                            <div class="text-2xl font-semibold text-amber-600">
                                {{ $total_resep_menunggu_apotek ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Resep completed yang belum diproses penjualan.
                            </p>
                        </div>
                    </div>

                    {{-- Resep draft --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Draft resep
                            </div>
                            <div class="text-2xl font-semibold text-sky-600">
                                {{ $total_resep_draft ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Resep yang belum dikirim / belum dilengkapi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Resep terbaru --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Resep terbaru
                            </h3>
                            <a href="{{ route('resep.index') }}"
                                class="text-xs font-medium text-primary-600 hover:text-primary-700">
                                Lihat semua
                            </a>
                        </div>

                        @if (!empty($daftar_resep_terbaru) && $daftar_resep_terbaru->count())
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-xs uppercase text-slate-400">
                                            <th class="py-2 pr-4">Pasien</th>
                                            <th class="py-2 pr-4">Tanggal</th>
                                            <th class="py-2 pr-4">Status</th>
                                            <th class="py-2 pr-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($daftar_resep_terbaru as $resep)
                                            <tr>
                                                <td class="py-2 pr-4 text-slate-700">
                                                    {{ $resep->pasien->nama_pasien ?? '-' }}
                                                </td>
                                                <td class="py-2 pr-4 text-slate-500 text-xs">
                                                    {{ optional($resep->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td class="py-2 pr-4 text-xs">
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2 py-0.5
                                                        text-[11px] font-medium
                                                        @class([
                                                            'bg-emerald-50 text-emerald-700' => $resep->status === 'completed',
                                                            'bg-amber-50 text-amber-700' => $resep->status === 'draft',
                                                            'bg-slate-50 text-slate-600' => !in_array($resep->status, [
                                                                'completed',
                                                                'draft',
                                                            ]),
                                                        ])
                                                    ">
                                                        {{ ucfirst($resep->status ?? '-') }}
                                                    </span>
                                                </td>
                                                <td class="py-2 pr-4 text-right">
                                                    <a href="{{ route('resep.show', $resep) }}"
                                                        class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-500">
                                Belum ada resep yang tercatat.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Resep menunggu apotek (detail list) --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Resep menunggu apotek
                        </h3>

                        @if (!empty($daftar_resep_menunggu_apotek) && $daftar_resep_menunggu_apotek->count())
                            <div class="mt-4 space-y-2">
                                @foreach ($daftar_resep_menunggu_apotek as $resep)
                                    <div
                                        class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-3 py-2">
                                        <div>
                                            <div class="text-sm font-medium text-slate-800">
                                                {{ $resep->pasien->nama_pasien ?? 'Pasien tanpa nama' }}
                                            </div>
                                            <div class="mt-0.5 text-xs text-slate-500">
                                                Resep #{{ $resep->id }} ·
                                                {{ optional($resep->created_at)->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">
                                                Menunggu apotek
                                            </span>
                                            <a href="{{ route('resep.show', $resep) }}"
                                                class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-500">
                                Tidak ada resep yang sedang menunggu apotek.
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- =======================
                 DASHBOARD: PHARMACIST
                 ======================= --}}
            @if ($role === 'pharmacist')
                {{-- Stat cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Resep perlu disiapkan --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Resep perlu disiapkan
                            </div>
                            <div class="text-2xl font-semibold text-slate-900">
                                {{ $prescriptions_to_prepare ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Resep completed yang menunggu penyiapan obat.
                            </p>
                        </div>
                    </div>

                    {{-- Resep siap diambil --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Resep siap diambil
                            </div>
                            <div class="text-2xl font-semibold text-emerald-600">
                                {{ $prescriptions_ready ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Obat yang sudah siap diserahkan ke pasien.
                            </p>
                        </div>
                    </div>

                    {{-- Penjualan hari ini (jumlah transaksi) --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Penjualan hari ini
                            </div>
                            <div class="text-2xl font-semibold text-sky-600">
                                {{ $sales_today_count ?? 0 }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Jumlah transaksi penjualan obat hari ini.
                            </p>
                        </div>
                    </div>

                    {{-- Total nilai penjualan hari ini --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                        <div class="p-4">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                Total nilai penjualan
                            </div>
                            <div class="text-2xl font-semibold text-amber-600">
                                Rp {{ number_format($sales_today_total ?? 0, 0, ',', '.') }}
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Akumulasi nilai penjualan obat hari ini.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Resep menunggu apotek untuk apoteker --}}
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Resep menunggu diproses
                        </h3>

                        @if (!empty($daftar_resep_menunggu) && $daftar_resep_menunggu->count())
                            <div class="mt-4 space-y-2">
                                @foreach ($daftar_resep_menunggu as $resep)
                                    <div
                                        class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-3 py-2">
                                        <div>
                                            <div class="text-sm font-medium text-slate-800">
                                                {{ $resep->pasien->nama_pasien ?? 'Pasien tanpa nama' }}
                                            </div>
                                            <div class="mt-0.5 text-xs text-slate-500">
                                                Resep #{{ $resep->id }} ·
                                                <a href="{{ route('resep.show', $resep) }}" class="underline">
                                                    {{ $resep->nomor_resep }}
                                                </a> ·
                                                {{ optional($resep->created_at)->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">
                                                Menunggu penyiapan
                                            </span>
                                            <a href="{{ route('resep.show', $resep) }}"
                                                class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                Proses
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-500">
                                Tidak ada resep yang menunggu saat ini.
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-sm font-semibold text-slate-800">
                                Penjualan terbaru
                            </h3>
                            <a href="{{ route('penjualan.index') }}"
                                class="text-xs font-medium text-primary-600 hover:text-primary-700">
                                Lihat semua
                            </a>
                        </div>

                        @if (!empty($penjualan_terbaru) && $penjualan_terbaru->count())
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-100 text-xs uppercase text-slate-400">
                                            <th class="py-2 pr-4">No. Transaksi</th>
                                            <th class="py-2 pr-4">Pasien</th>
                                            <th class="py-2 pr-4">Tanggal</th>
                                            <th class="py-2 pr-4 text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach ($penjualan_terbaru as $pj)
                                            <tr>
                                                <td class="py-2 pr-4 text-slate-700">
                                                    <a href="{{ route('penjualan.show', $pj) }}" class="underline">
                                                        {{ $pj->nomor_transaksi }}
                                                    </a>
                                                </td>
                                                <td class="py-2 pr-4 text-slate-700">
                                                    {{ $pj->resep->pasien->nama_pasien ?? '-' }}
                                                </td>
                                                <td class="py-2 pr-4 text-slate-500 text-xs">
                                                    {{ optional($pj->created_at)->format('d M Y H:i') }}
                                                </td>
                                                <td class="py-2 pr-4 text-right text-slate-800">
                                                    Rp {{ number_format($pj->total_harga, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mt-3 text-sm text-slate-500">
                                Belum ada penjualan tercatat.
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- =======================
                 DASHBOARD: DEFAULT / ROLE LAIN
                 ======================= --}}
            @if ($role !== 'doctor' && $role !== 'pharmacist')
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-slate-800">
                            Dashboard Umum
                        </h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Tambahkan konten khusus untuk role <span
                                class="font-mono text-xs px-1.5 py-0.5 rounded bg-slate-100 text-slate-700">{{ $role }}</span>
                            di sini jika dibutuhkan.
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
