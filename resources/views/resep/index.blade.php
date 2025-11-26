<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Resep Saya
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Daftar resep yang Anda buat.
                </p>
            </div>
            @can('create', App\Models\Resep::class)
                <a href="{{ route('resep.create') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-emerald-600 text-white hover:bg-emerald-700">
                    + Buat Resep
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Filter status --}}
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Filter:</span>
                <a href="{{ route('resep.index') }}"
                    class="text-xs px-2 py-1 rounded-full border
                        {{ $status ? 'border-slate-200 text-slate-500 bg-white' : 'border-emerald-500 text-emerald-700 bg-emerald-50' }}">
                    Semua
                </a>
                @if (auth()->user()->role === 'doctor')
                    <a href="{{ route('resep.index', ['status' => 'draft']) }}"
                        class="text-xs px-2 py-1 rounded-full border
                            {{ $status === 'draft' ? 'border-emerald-500 text-emerald-700 bg-emerald-50' : 'border-slate-200 text-slate-500 bg-white' }}">
                        Draft
                    </a>
                @endif
                <a href="{{ route('resep.index', ['status' => 'completed']) }}"
                    class="text-xs px-2 py-1 rounded-full border
                        {{ $status === 'completed' ? 'border-emerald-500 text-emerald-700 bg-emerald-50' : 'border-slate-200 text-slate-500 bg-white' }}">
                    Completed
                </a>
                @if (auth()->user()->role === 'pharmacist')
                    <a href="{{ route('resep.index', ['status' => 'processed']) }}"
                        class="text-xs px-2 py-1 rounded-full border
                        {{ $status === 'processed' ? 'border-rose-500 text-rose-700 bg-rose-50' : 'border-slate-200 text-slate-500 bg-white' }}">
                        Processed
                    </a>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Nomor Resep
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Pasien
                            </th>
                            @if (auth()->user()->role !== 'doctor')
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Dokter
                                </th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reseps as $resep)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">
                                    {{ $resep->nomor_resep }}
                                </td>
                                <td class="px-4 py-3 text-slate-700">
                                    {{ $resep->pasien->nama_pasien ?? '-' }}
                                </td>
                                @if (auth()->user()->role !== 'doctor')
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $resep->dokter->name ?? '-' }}
                                    </td>
                                @endif
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium
                                        @class([
                                            'bg-slate-100 text-slate-700' => $resep->status === 'draft',
                                            'bg-emerald-50 text-emerald-700' => $resep->status === 'completed',
                                        ])">
                                        {{ ucfirst($resep->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-700">
                                    {{ optional($resep->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('resep.show', $resep) }}"
                                            class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                            Detail
                                        </a>
                                        @if ($resep->status === 'draft')
                                            @can('update', $resep)
                                                <a href="{{ route('resep.edit', $resep) }}"
                                                    class="inline-flex items-center rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700 hover:border-primary-500 hover:text-primary-600">
                                                    Edit
                                                </a>
                                            @endcan
                                        @endif
                                        @if ($resep->status === 'draft')
                                            @can('complete', $resep)
                                                <form action="{{ route('resep.complete', $resep) }}" method="POST"
                                                    onsubmit="return confirm('Kirim resep ini ke apotek? Setelah dikirim, resep tidak bisa diedit lagi.');"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-full border border-emerald-200 px-3 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50 hover:border-emerald-300">
                                                        Kirim ke Apotek
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif

                                        @if ($resep->status === 'completed')
                                            @can('process', $resep)
                                                <form action="{{ route('resep.process', $resep) }}" method="POST"
                                                    onsubmit="return confirm('Proses resep ini di apotek?');"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center rounded-full border border-rose-200 px-3 py-1 text-xs font-medium text-rose-700 hover:bg-rose-50 hover:border-rose-300">
                                                        Proses di Apotek
                                                    </button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                    Belum ada resep.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-4 py-3 border-t border-slate-100">
                    {{ $reseps->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
