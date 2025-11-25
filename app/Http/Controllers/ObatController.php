<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        if ($request->user()->cannot('viewAny', Obat::class)) {
            abort(403);
        }

        $search = $request->query('q');

        $obat = Obat::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_obat', 'like', "%{$search}%")
                    ->orWhere('kode_obat', 'like', "%{$search}%");
            })
            ->orderBy('nama_obat')
            ->paginate(15)
            ->withQueryString();

        return view('obat.index', compact('obat', 'search'));
    }

    public function create(Request $request): View
    {
        if ($request->user()->cannot('create', Obat::class)) {
            abort(403);
        }

        return view('obat.partials.create');
    }

    public function store(Request $request): RedirectResponse
    {

        if ($request->user()->cannot('create', Obat::class)) {
            abort(403);
        }

        $validated = $request->validate([
            'kode_obat'  => ['required', 'string', 'max:50', 'unique:obats,kode_obat'],
            'nama_obat'  => ['required', 'string', 'max:255'],
            'stok'       => ['nullable', 'integer', 'min:0'],
            'harga_jual' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['stok']       ??= 0;
        $validated['harga_jual'] ??= 0;

        Obat::create($validated);

        return redirect()
            ->route('obat.index')
            ->with('status', 'Obat berhasil ditambahkan.');
    }

    public function show(Request $request, Obat $obat): View
    {
        if ($request->user()->cannot('view', $obat)) {
            abort(403);
        }

        return view('obat.partials.show', compact('obat'));
    }


    public function edit(Request $request, Obat $obat): View
    {
        if ($request->user()->cannot('update', $obat)) {
            abort(403);
        }

        return view('obat.partials.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        if ($request->user()->cannot('update', $obat)) {
            abort(403);
        }

        $validated = $request->validate([
            'kode_obat'  => ['required', 'string', 'max:50', 'unique:obats,kode_obat,' . $obat->id],
            'nama_obat'  => ['required', 'string', 'max:255'],
            'stok'       => ['nullable', 'integer', 'min:0'],
            'harga_jual' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['stok']       ??= 0;
        $validated['harga_jual'] ??= 0;

        $obat->update($validated);

        return redirect()
            ->route('obat.index')
            ->with('status', 'Obat berhasil diperbarui.');
    }

    public function destroy(Request $request, Obat $obat): RedirectResponse
    {
        if ($request->user()->cannot('delete', $obat)) {
            abort(403);
        }

        $obat->delete();

        return redirect()
            ->route('obat.index')
            ->with('status', 'Obat berhasil dihapus.');
    }
}
