<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Pasien
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Daftar pasien saat ini, anda bisa membuat resep untuk pasien yang dipilih.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            List Pasien
                        </h3>
                        <p class="mt-1 text-xs text-slate-500">
                            Cari pasien berdasarkan nama atau nomor rekam medis.
                        </p>
                    </div>

                    {{-- Form pencarian --}}
                    <form method="GET" action="{{ route('pasien.index') }}" class="w-full md:w-auto">
                        <div class="flex gap-2">
                            <input type="text" name="q" value="{{ $search }}"
                                placeholder="Cari nama / No. RM..."
                                class="block w-full md:w-64 rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @if ($search)
                                <a href="{{ route('pasien.index') }}"
                                    class="inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    No. RM
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Nama Pasien
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Ditambahkan
                                </th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($pasiens as $pasien)
                                <tr>
                                    <td class="px-4 py-3 font-mono text-[13px] text-slate-800">
                                        {{ $pasien->no_rekam_medis }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $pasien->nama_pasien }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 text-xs">
                                        {{ optional($pasien->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('resep.create', ['pasien_id' => $pasien->id]) }}"
                                                class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                Buat Resep
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                        Belum ada data pasien.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pasiens->hasPages())
                    <div class="mt-4">
                        {{ $pasiens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
