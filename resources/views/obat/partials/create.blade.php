<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Obat
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Input data obat baru.
                </p>
            </div>
            <a href="{{ route('obat.index') }}"
                class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <form method="POST" action="{{ route('obat.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="kode_obat" value="Kode Obat" />
                            <x-text-input id="kode_obat" name="kode_obat" type="text" class="mt-1 block w-full"
                                value="{{ old('kode_obat') }}" required />
                            <x-input-error :messages="$errors->get('kode_obat')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="nama_obat" value="Nama Obat" />
                            <x-text-input id="nama_obat" name="nama_obat" type="text" class="mt-1 block w-full"
                                value="{{ old('nama_obat') }}" required />
                            <x-input-error :messages="$errors->get('nama_obat')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="stok" value="Stok" />
                            <x-text-input id="stok" name="stok" type="number" min="0"
                                class="mt-1 block w-full" value="{{ old('stok', 0) }}" />
                            <x-input-error :messages="$errors->get('stok')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="harga_jual" value="Harga Jual" />
                            <x-text-input id="harga_jual" name="harga_jual" type="number" step="0.01" min="0"
                                class="mt-1 block w-full" value="{{ old('harga_jual', 0) }}" />
                            <x-input-error :messages="$errors->get('harga_jual')" class="mt-1" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('obat.index') }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                        <x-primary-button>
                            Simpan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
