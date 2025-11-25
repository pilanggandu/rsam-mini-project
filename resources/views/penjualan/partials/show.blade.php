{{-- resources/views/penjualan/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Penjualan {{ $penjualan->nomor_transaksi }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Dari resep {{ $penjualan->resep->nomor_resep ?? '-' }}
                    untuk {{ $penjualan->resep->pasien->nama_pasien ?? '-' }}.
                </p>
            </div>
            <a href="{{ route('penjualan.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Info utama --}}
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Pasien
                        </div>
                        <div class="text-slate-800">
                            {{ $penjualan->resep->pasien->nama_pasien ?? '-' }}
                        </div>
                        <div class="mt-0.5 text-xs text-slate-500">
                            No. RM: {{ $penjualan->resep->pasien->no_rekam_medis ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Dokter
                        </div>
                        <div class="text-slate-800">
                            {{ $penjualan->resep->dokter->name ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Apoteker
                        </div>
                        <div class="text-slate-800">
                            {{ $penjualan->apoteker->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm pt-4 border-t border-slate-100">
                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Tanggal Transaksi
                        </div>
                        <div class="text-slate-800">
                            {{ optional($penjualan->created_at)->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Status
                        </div>
                        <div class="text-slate-800">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium
                                    @class([
                                        'bg-slate-100 text-slate-700' => $penjualan->status === 'draft',
                                        'bg-emerald-50 text-emerald-700' => $penjualan->status === 'paid',
                                    ])">
                                @if ($penjualan->status === 'draft')
                                    Draft
                                @elseif ($penjualan->status === 'paid')
                                    Selesai
                                @else
                                    {{ ucfirst($penjualan->status) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Diselesaikan Pada
                        </div>
                        <div class="text-slate-800">
                            {{ $penjualan->finalized_at ? $penjualan->finalized_at->format('d/m/Y H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                    <div>
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Total Item
                        </div>
                        <div class="text-slate-800">
                            {{ $penjualan->details->sum('jumlah') }} item
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Total Harga
                        </div>
                        <div class="text-lg font-semibold text-slate-900">
                            Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail obat --}}
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Detail Penjualan
                    </h3>

                    {{-- Tombol finalize kalau masih draft --}}
                    @if ($penjualan->status !== 'paid' && in_array(auth()->user()->role, ['pharmacist', 'admin'], true))
                        <form action="{{ route('penjualan.finalize', $penjualan) }}" method="POST"
                            onsubmit="return confirm('Selesaikan penjualan ini dan kurangi stok obat?');">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs md:text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                                Bayar
                            </button>
                        </form>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Nama Obat
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Harga Satuan
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($penjualan->details as $detail)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->obat->kode_obat ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->obat->nama_obat ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-800">
                                        {{ $detail->jumlah }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-800">
                                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-800">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                        Tidak ada detail penjualan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
