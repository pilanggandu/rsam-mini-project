{{-- resources/views/resep/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Resep {{ $resep->nomor_resep }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Dibuat oleh {{ $resep->dokter->name ?? 'Dokter' }}
                    pada {{ optional($resep->created_at)->format('d/m/Y H:i') }}.
                </p>
            </div>
            <a href="{{ route('resep.index') }}"
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

            {{-- Info pasien, dokter & status --}}
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6 space-y-4">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="space-y-3">
                        {{-- Pasien --}}
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">
                                Pasien
                            </h3>
                            <div class="text-sm text-slate-800">
                                {{ $resep->pasien->nama_pasien ?? '-' }}
                            </div>
                            <div class="mt-0.5 text-xs text-slate-500">
                                No. RM: {{ $resep->pasien->no_rekam_medis ?? '-' }}
                            </div>
                        </div>

                        {{-- Dokter --}}
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-1">
                                Dokter
                            </h3>
                            <div class="text-sm text-slate-800">
                                {{ $resep->dokter->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Status Resep
                        </div>
                        <div>
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
                                        Completed
                                    @break

                                    @case('processed')
                                        Diproses Apotek
                                    @break

                                    @default
                                        {{ ucfirst($resep->status) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                @if ($resep->catatan)
                    <div class="pt-3 border-t border-slate-100">
                        <div class="text-xs font-medium text-slate-500 mb-1">
                            Catatan Dokter
                        </div>
                        <p class="text-sm text-slate-800 whitespace-pre-line">
                            {{ $resep->catatan }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Detail obat --}}
            <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Daftar Obat
                    </h3>

                    <div class="flex items-center gap-2">
                        {{-- Dokter boleh edit kalau masih draft --}}
                        @if ($resep->status === 'draft')
                            @can('update', $resep)
                                <a href="{{ route('resep.edit', $resep) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs md:text-sm font-medium bg-slate-100 text-slate-800 hover:bg-slate-200">
                                    Edit Resep
                                </a>
                            @endcan
                        @endif

                        {{-- Apoteker: proses resep kalau status completed --}}
                        @if ($resep->status === 'completed')
                            @can('process', $resep)
                                <form action="{{ route('resep.process', $resep) }}" method="POST"
                                    onsubmit="return confirm('Proses resep ini di apotek?');">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs md:text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                                        Proses di Apotek
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </div>
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
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Dosis
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($resep->details as $detail)
                                <tr>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->obat->kode_obat ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->obat->nama_obat ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->jumlah }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800">
                                        {{ $detail->dosis }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                        Tidak ada detail obat.
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
