<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Obat
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap untuk obat <span class="font-semibold">{{ $obat->nama_obat }}</span>.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('obat.index') }}"
                    class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Kembali
                </a>
                @if (auth()->user()->role === 'pharmacist')
                    <a href="{{ route('obat.edit', $obat) }}"
                        class="inline-flex items-center rounded-xl bg-yellow-400 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500">
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Kode Obat
                        </div>
                        <div class="mt-1 text-lg font-semibold text-slate-900">
                            {{ $obat->kode_obat }}
                        </div>
                        <div class="mt-3 text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Nama Obat
                        </div>
                        <div class="mt-1 text-base font-semibold text-slate-900">
                            {{ $obat->nama_obat }}
                        </div>
                    </div>

                    <div class="md:text-right">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Harga Jual
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-emerald-600">
                            Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}
                        </div>

                        <div class="mt-4 text-xs font-medium text-slate-500 uppercase tracking-wide">
                            Stok
                        </div>
                        <div class="mt-1 inline-flex items-center gap-2">
                            <span class="text-lg font-semibold text-slate-900">
                                {{ $obat->stok }}
                            </span>
                            <span
                                class="rounded-full bg-slate-50 px-2 py-0.5 text-[11px] font-medium text-slate-500 border border-slate-100">
                                Unit
                            </span>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- Info meta --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-500">
                    <div>
                        <div class="uppercase tracking-wide font-medium text-slate-500">
                            Dibuat
                        </div>
                        <div class="mt-1 text-slate-700">
                            {{ optional($obat->created_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="uppercase tracking-wide font-medium text-slate-500">
                            Terakhir diperbarui
                        </div>
                        <div class="mt-1 text-slate-700">
                            {{ optional($obat->updated_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                    @can('update', $obat)
                        <p class="text-xs text-slate-500">
                            Gunakan tombol <span class="font-semibold">Edit</span> di atas jika ingin mengubah data obat
                            ini.
                        </p>
                    @endcan

                    @can('delete', $obat)
                        <form action="{{ route('obat.destroy', $obat) }}" method="POST"
                            onsubmit="return confirm('Hapus obat ini? Data tidak dapat dikembalikan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center rounded-xl border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                Hapus Obat
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
