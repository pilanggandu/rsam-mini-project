{{-- resources/views/resep/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Resep {{ $resep->nomor_resep }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui detail resep ini. Resep yang sudah completed tidak dapat diedit.
                </p>
            </div>
            <a href="{{ route('resep.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('update', $resep)
                @php
                    $initialDetails = old('details');
                    if (!$initialDetails) {
                        $initialDetails = $resep->details
                            ? $resep->details
                                ->map(function ($d) {
                                    return [
                                        'obat_id' => $d->obat_id,
                                        'jumlah' => $d->jumlah,
                                        'dosis' => $d->dosis,
                                    ];
                                })
                                ->toArray()
                            : [];

                        if (empty($initialDetails)) {
                            $initialDetails = [['obat_id' => '', 'jumlah' => 1, 'dosis' => '']];
                        }
                    }
                @endphp

                <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6"
                    x-data='{
                        details: @json($initialDetails),
                        addRow() {
                            this.details.push({ obat_id: "", jumlah: 1, dosis: "" });
                        },
                        removeRow(index) {
                            if (this.details.length > 1) {
                                this.details.splice(index, 1);
                            }
                        }
                    }'>
                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('resep.update', $resep) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">
                                    Pasien <span class="text-rose-500">*</span>
                                </label>
                                <select name="pasien_id"
                                    class="mt-1 block w-full rounded-lg border-slate-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                    <option value="">Pilih pasien</option>
                                    @foreach ($pasiens as $pasien)
                                        <option value="{{ $pasien->id }}" @selected(old('pasien_id', $resep->pasien_id) == $pasien->id)>
                                            {{ $pasien->no_rekam_medis }} - {{ $pasien->nama_pasien }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pasien_id')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">
                                Catatan (opsional)
                            </label>
                            <textarea name="catatan" rows="3"
                                class="mt-1 block w-full rounded-lg border-slate-300 text-sm focus:border-primary-500 focus:ring-primary-500">{{ old('catatan', $resep->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Detail resep --}}
                        <div class="border-t border-slate-100 pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-slate-900">
                                    Detail Resep
                                </h3>
                                <button type="button" @click="addRow()"
                                    class="inline-flex items-center text-xs px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200">
                                    + Tambah Obat
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(row, index) in details" :key="index">
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        {{-- Obat --}}
                                        <div class="col-span-12 md:col-span-5">
                                            <label class="block text-[11px] font-medium text-slate-600 mb-1">
                                                Obat
                                            </label>
                                            <select
                                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                                                :name="`details[${index}][obat_id]`" x-model="row.obat_id" required>
                                                <option value="">Pilih obat</option>
                                                @foreach ($obats as $obat)
                                                    <option value="{{ $obat->id }}">
                                                        {{ $obat->kode_obat }} - {{ $obat->nama_obat }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Jumlah --}}
                                        <div class="col-span-6 md:col-span-2">
                                            <label class="block text-[11px] font-medium text-slate-600 mb-1">
                                                Jumlah
                                            </label>
                                            <input type="number" min="1"
                                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                                                :name="`details[${index}][jumlah]`" x-model.number="row.jumlah" required>
                                        </div>

                                        {{-- Dosis --}}
                                        <div class="col-span-6 md:col-span-4">
                                            <label class="block text-[11px] font-medium text-slate-600 mb-1">
                                                Dosis
                                            </label>
                                            <input type="text" placeholder="cth: 3x1 sesudah makan"
                                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                                                :name="`details[${index}][dosis]`" x-model="row.dosis" required>
                                        </div>

                                        {{-- Hapus --}}
                                        <div class="col-span-12 md:col-span-1 flex justify-start md:justify-end">
                                            <button type="button" @click="removeRow(index)" x-show="details.length > 1"
                                                class="mt-2 md:mt-0 inline-flex items-center justify-center
                                                       h-10 w-full
                                                       rounded-lg border border-rose-300 bg-white
                                                       text-rose-600 hover:bg-rose-50 hover:border-rose-400
                                                       focus:outline-none focus:ring-2 focus:ring-rose-400/40 text-xs"
                                                aria-label="Hapus baris">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-100 p-6 text-sm text-slate-600">
                    <p class="font-semibold text-slate-800">
                        Anda tidak memiliki izin untuk mengedit resep ini.
                    </p>
                    <p class="mt-2">
                        Silakan hubungi administrator atau kembali ke detail resep.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('resep.show', $resep) }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Kembali ke detail
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
