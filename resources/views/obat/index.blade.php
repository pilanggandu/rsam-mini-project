<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @if (auth()->user()->role === 'pharmacist')
                        Master
                    @else
                        Daftar
                    @endif
                    Obat
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    @if (auth()->user()->role === 'pharmacist')
                        Kelola
                    @else
                        Lihat
                    @endif
                    daftar obat (kode, nama, stok, harga jual).
                </p>
            </div>
            @can('create', App\Models\Obat::class)
                <a href="{{ route('obat.create') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                    + Tambah Obat
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                <form method="GET" action="{{ route('obat.index') }}"
                    class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
                    <div class="text-sm text-slate-600">
                        Cari obat berdasarkan nama atau kode.
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $search }}"
                            class="block w-52 rounded-xl border-slate-200 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Cari nama / kode...">
                        @if ($search)
                            <a href="{{ route('obat.index') }}" class="text-xs text-slate-500 hover:text-slate-700">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Nama Obat</th>
                                <th class="px-4 py-3 text-right">Stok</th>
                                <th class="px-4 py-3 text-right">Harga Jual</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($obat as $item)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-2 text-xs font-mono text-slate-600">
                                        {{ $item->kode_obat }}
                                    </td>
                                    <td class="px-4 py-2 text-slate-800">
                                        {{ $item->nama_obat }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-sm text-slate-700">
                                        {{ $item->stok }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-sm text-slate-700">
                                        Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('obat.show', $item) }}"
                                                class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                Detail
                                            </a>
                                            @can('update', $item)
                                                <a href="{{ route('obat.edit', $item) }}"
                                                    class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('delete', $item)
                                                <form action="{{ route('obat.destroy', $item) }}" method="POST"
                                                    onsubmit="return confirm('Hapus obat ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-full border border-rose-200 px-3 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                        Belum ada data obat.
                                        @can('create', App\Models\Obat::class)
                                            <a href="{{ route('obat.create') }}"
                                                class="font-semibold text-primary-600 hover:text-primary-700">
                                                Tambah obat pertama
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($obat->hasPages())
                    <div class="border-t border-slate-100 px-4 py-3">
                        {{ $obat->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
