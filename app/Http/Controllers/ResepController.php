<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Resep;
use App\Models\ResepDetail;
use Illuminate\Http\Request;

class ResepController extends Controller
{
    /**
     * Tampilkan daftar resep milik dokter yang login.
     */
    public function index(Request $request)
    {
        $user   = $request->user();
        $status = $request->get('status'); // optional: draft / completed

        $query = Resep::with('pasien')
            ->where('dokter_id', $user->id)
            ->latest();

        if ($status && in_array($status, ['draft', 'completed'], true)) {
            $query->where('status', $status);
        }

        $reseps = $query->paginate(10);

        return view('resep.index', compact('reseps', 'status'));
    }

    /**
     * Form buat resep baru.
     */
    public function create()
    {
        $pasiens = Pasien::orderBy('nama_pasien')->get();
        $obats   = Obat::orderBy('nama_obat')->get();

        return view('resep.create', compact('pasiens', 'obats'));
    }

    /**
     * Simpan resep baru.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'pasien_id'           => ['required', 'exists:pasiens,id'],
            'status'              => ['required', 'in:draft,completed'],
            'catatan'             => ['nullable', 'string'],
            'details'             => ['required', 'array', 'min:1'],
            'details.*.obat_id'   => ['required', 'exists:obats,id'],
            'details.*.jumlah'    => ['required', 'integer', 'min:1'],
            'details.*.dosis'     => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $nomorResep = $this->generateNomorResep();

            $resep = Resep::create([
                'nomor_resep' => $nomorResep,
                'pasien_id'   => $validated['pasien_id'],
                'dokter_id'   => $user->id,
                'status'      => $validated['status'],
                'catatan'     => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['details'] as $detail) {
                ResepDetail::create([
                    'resep_id' => $resep->id,
                    'obat_id'  => $detail['obat_id'],
                    'jumlah'   => $detail['jumlah'],
                    'dosis'    => $detail['dosis'],
                ]);
            }
        });

        return redirect()
            ->route('resep.index')
            ->with('status', 'Resep berhasil dibuat.');
    }

    /**
     * Detail resep.
     */
    public function show(Resep $resep)
    {
        $this->authorizeResep($resep);

        $resep->load(['pasien', 'dokter', 'details.obat']);

        return view('resep.show', compact('resep'));
    }

    /**
     * Form edit resep (hanya untuk status draft).
     */
    public function edit(Resep $resep)
    {
        $this->authorizeResep($resep);

        if ($resep->status !== 'draft') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep yang sudah completed tidak dapat diedit.');
        }

        $pasiens = Pasien::orderBy('nama_pasien')->get();
        $obats   = Obat::orderBy('nama_obat')->get();

        $resep->load('details');

        return view('resep.edit', compact('resep', 'pasiens', 'obats'));
    }

    /**
     * Update resep (draft → draft / completed).
     */
    public function update(Request $request, Resep $resep)
    {
        $this->authorizeResep($resep);

        if ($resep->status !== 'draft') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep yang sudah completed tidak dapat diedit.');
        }

        $validated = $request->validate([
            'pasien_id'           => ['required', 'exists:pasiens,id'],
            'status'              => ['required', 'in:draft,completed'],
            'catatan'             => ['nullable', 'string'],
            'details'             => ['required', 'array', 'min:1'],
            'details.*.obat_id'   => ['required', 'exists:obats,id'],
            'details.*.jumlah'    => ['required', 'integer', 'min:1'],
            'details.*.dosis'     => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $resep) {
            $resep->update([
                'pasien_id' => $validated['pasien_id'],
                'status'    => $validated['status'],
                'catatan'   => $validated['catatan'] ?? null,
            ]);

            // hapus detail lama, buat ulang
            $resep->details()->delete();

            foreach ($validated['details'] as $detail) {
                ResepDetail::create([
                    'resep_id' => $resep->id,
                    'obat_id'  => $detail['obat_id'],
                    'jumlah'   => $detail['jumlah'],
                    'dosis'    => $detail['dosis'],
                ]);
            }
        });

        return redirect()
            ->route('resep.show', $resep)
            ->with('status', 'Resep berhasil diperbarui.');
    }

    // ===================== Helper =====================

    /**
     * Generate nomor resep dengan format RXYYYYMMDDxxx
     * contoh: RX20251124001
     */
    protected function generateNomorResep(): string
    {
        $prefix = 'RX' . now()->format('Ymd');

        $last = Resep::where('nomor_resep', 'like', $prefix . '%')
            ->orderBy('nomor_resep', 'desc')
            ->first();

        $nextNumber = 1;
        if ($last) {
            $lastRunning = (int) substr($last->nomor_resep, -3);
            $nextNumber  = $lastRunning + 1;
        }

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Pastikan resep milik dokter yang login.
     */
    protected function authorizeResep(Resep $resep): void
    {
        if (auth()->id() !== $resep->dokter_id) {
            abort(403, 'Anda tidak berhak mengakses resep ini.');
        }
    }
}
