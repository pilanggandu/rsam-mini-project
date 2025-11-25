<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PenjualanController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status');

        $query = Penjualan::with(['resep.pasien', 'apoteker'])
            ->orderByDesc('created_at');

        if ($status && in_array($status, ['pending', 'paid'], true)) {
            $query->where('status', $status);
        }

        $penjualans = $query->paginate(15)->withQueryString();

        return view('penjualan.index', compact('penjualans', 'status'));
    }

    public function show(Penjualan $penjualan): View
    {
        $penjualan->load(['resep.pasien', 'resep.dokter', 'apoteker', 'details.obat']);

        return view('penjualan.partials.show', compact('penjualan'));
    }

    public function finalize(Request $request, Penjualan $penjualan): RedirectResponse
    {
        // Sederhana: hanya pharmacist/admin
        if (! in_array($request->user()->role, ['pharmacist', 'admin'], true)) {
            abort(403);
        }

        // Cegah finalize 2x
        if ($penjualan->status === 'paid') {
            return redirect()
                ->route('penjualan.show', $penjualan)
                ->with('error', 'Penjualan ini sudah diselesaikan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($penjualan) {
                $penjualan->loadMissing('details.obat');

                foreach ($penjualan->details as $detail) {
                    $obat = Obat::whereKey($detail->obat_id)->lockForUpdate()->first();

                    if (! $obat) {
                        throw new \RuntimeException('Obat pada detail penjualan tidak ditemukan.');
                    }

                    if ($obat->stok < $detail->jumlah) {
                        throw new \RuntimeException(
                            "Stok obat {$obat->nama_obat} tidak mencukupi. Stok: {$obat->stok}, dibutuhkan: {$detail->jumlah}."
                        );
                    }

                    $obat->decrement('stok', $detail->jumlah);
                }

                $penjualan->update([
                    'status'       => 'paid',
                    'finalized_at' => now(),
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()
                ->route('penjualan.show', $penjualan)
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('penjualan.show', $penjualan)
            ->with('status', 'Penjualan berhasil diselesaikan & stok sudah dikurangi.');
    }
}
