<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Riwayat Penjualan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Daftar transaksi penjualan obat dari resep dokter.
                </p>
            </div>
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

            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Daftar Penjualan
                    </h3>

                    {{-- contoh kecil: info filter --}}
                    <div class="text-xs text-slate-500">
                        Menampilkan {{ $penjualans->firstItem() ?? 0 }}–{{ $penjualans->lastItem() ?? 0 }}
                        dari {{ $penjualans->total() }} transaksi
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500">Filter:</span>
                    <a href="{{ route('penjualan.index') }}"
                        class="text-xs px-2 py-1 rounded-full border
                        {{ $status ? 'border-slate-200 text-slate-500 bg-white' : 'border-emerald-500 text-emerald-700 bg-emerald-50' }}">
                        Semua
                    </a>
                    <a href="{{ route('penjualan.index', ['status' => 'pending']) }}"
                        class="text-xs px-2 py-1 rounded-full border
                        {{ $status === 'pending' ? 'border-emerald-500 text-emerald-700 bg-emerald-50' : 'border-slate-200 text-slate-500 bg-white' }}">
                        Tertunda
                    </a>
                    <a href="{{ route('penjualan.index', ['status' => 'paid']) }}"
                        class="text-xs px-2 py-1 rounded-full border
                        {{ $status === 'paid' ? 'border-emerald-500 text-emerald-700 bg-emerald-50' : 'border-slate-200 text-slate-500 bg-white' }}">
                        Lunas
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    No. Transaksi
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    No. Resep
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Pasien
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Apoteker
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($penjualans as $penjualan)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-slate-900">
                                        {{ $penjualan->nomor_transaksi }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $penjualan->resep->nomor_resep ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $penjualan->resep->pasien->nama_pasien ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $penjualan->apoteker->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
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
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-800">
                                        Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('penjualan.show', $penjualan) }}"
                                                class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                Detail
                                            </a>
                                            @if ($penjualan->status !== 'paid' && auth()->user()->role === 'pharmacist')
                                                <form action="{{ route('penjualan.finalize', $penjualan) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Selesaikan penjualan ini dan kurangi stok obat?');"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                                                        Bayar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                                        Belum ada penjualan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($penjualans->hasPages())
                    <div class="mt-4">
                        {{ $penjualans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
