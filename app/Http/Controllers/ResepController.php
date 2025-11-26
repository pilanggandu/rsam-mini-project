<?php

namespace App\Http\Controllers;

use App\Exceptions\StokTidakCukupException;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Resep;
use App\Models\ResepDetail;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ResepController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Resep::class)) {
            abort(403);
        }

        $user   = $request->user();
        $status = $request->get('status');

        if($user->role === 'doctor') {
            $query = Resep::with('pasien')
                ->where('dokter_id', $user->id)
                ->latest();
        } else {
            $query = Resep::with('pasien', 'dokter')
                ->latest();
        }

        if ($status && in_array($status, ['draft', 'completed', 'processed'], true)) {
            $query->where('status', $status);
        }

        $reseps = $query->paginate(10);

        return view('resep.index', compact('reseps', 'status'));
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Resep::class)) {
            abort(403);
        }

        $pasiens = Pasien::orderBy('nama_pasien')->get();
        $obats   = Obat::orderBy('nama_obat')->get();

        $selectedPasienId = (int) $request->query('pasien_id');

        if ($selectedPasienId && ! $pasiens->contains('id', $selectedPasienId)) {
            $selectedPasienId = null;
        }

        return view('resep.partials.create', compact(
            'pasiens',
            'obats',
            'selectedPasienId',
        ));
    }

    public function store(Request $request)
    {

        if ($request->user()->cannot('create', Resep::class)) {
            abort(403);
        }

        $user = $request->user();

        $validated = $request->validate([
            'pasien_id'            => ['required', 'exists:pasiens,id'],
            'catatan'              => ['nullable', 'string', 'max:1000'],

            'details'              => ['required', 'array', 'min:1'],
            'details.*.obat_id'    => ['required', 'integer', 'exists:obats,id', 'distinct'],
            'details.*.jumlah'     => ['required', 'integer', 'min:1'],
            'details.*.dosis'      => ['required', 'string', 'max:255'],
        ], [
            'details.*.obat_id.distinct' => 'Obat yang sama tidak boleh dipilih lebih dari satu kali.',
            'details.*.obat_id.required' => 'Pilih obat untuk setiap baris resep.',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $resep = Resep::create([
                'nomor_resep' => Resep::generateNomorResep(),
                'pasien_id'   => $validated['pasien_id'],
                'dokter_id'   => $user->id,
                'status'      => 'draft',
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

    public function show(Request $request, Resep $resep)
    {
        if ($request->user()->cannot('view', $resep)) {
            abort(403);
        }

        $resep->load(['pasien', 'dokter', 'details.obat']);

        return view('resep.partials.show', compact('resep'));
    }

    public function edit(Request $request, Resep $resep)
    {
        if ($request->user()->cannot('update', $resep)) {
            abort(403);
        }

        if ($resep->status !== 'draft') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep yang sudah completed tidak dapat diedit.');
        }

        $pasiens = Pasien::orderBy('nama_pasien')->get();
        $obats   = Obat::orderBy('nama_obat')->get();

        $resep->load('details');

        return view('resep.partials.edit', compact('resep', 'pasiens', 'obats'));
    }

    public function update(Request $request, Resep $resep)
    {
        if ($request->user()->cannot('update', $resep)) {
            abort(403);
        }

        if ($resep->status !== 'draft') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep yang sudah completed tidak dapat diedit.');
        }

        $validated = $request->validate([
            'pasien_id'            => ['required', 'exists:pasiens,id'],
            'catatan'              => ['nullable', 'string', 'max:1000'],

            'details'              => ['required', 'array', 'min:1'],
            'details.*.obat_id'    => ['required', 'integer', 'exists:obats,id', 'distinct'],
            'details.*.jumlah'     => ['required', 'integer', 'min:1'],
            'details.*.dosis'      => ['required', 'string', 'max:255'],
        ], [
            'details.*.obat_id.distinct' => 'Obat yang sama tidak boleh dipilih lebih dari satu kali.',
        ]);

        DB::transaction(function () use ($validated, $resep) {
            $resep->update([
                'pasien_id' => $validated['pasien_id'],
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

    public function complete(Request $request, Resep $resep): RedirectResponse
    {
        if ($request->user()->cannot('complete', $resep)) {
            abort(403);
        }

        if ($resep->status !== 'draft') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep ini tidak dapat dikirim ke apotek.');
        }

        $resep->status = 'completed';
        $resep->updated_at = now();
        $resep->save();

        return redirect()
            ->route('resep.show', $resep)
            ->with('status', 'Resep berhasil dikirim ke apotek.');
    }

    public function process(Request $request, Resep $resep): RedirectResponse
    {
        if ($request->user()->cannot('process', $resep)) {
            abort(403);
        }

        if ($resep->status === 'processed') {
            return redirect()
                ->route('resep.show', $resep)
                ->with('error', 'Resep ini sudah diproses oleh apotek.');
        }

            try {
                $penjualan = $this->createPenjualanFromResep($resep, $request->user());
             } catch (StokTidakCukupException $e) {
                return redirect()
                    ->route('resep.show', $resep)
                    ->with('error', $e->getMessage());
            }

        return redirect()
            ->route('resep.show', $resep)
            ->with('status', 'Resep berhasil diproses oleh apotek.');
    }

    // ===================== HELPER =====================

    protected function createPenjualanFromResep(Resep $resep, User $apoteker): Penjualan
    {
        return DB::transaction(function () use ($resep, $apoteker) {
            // pastikan relasi sudah ke-load
            $resep->loadMissing('details.obat');

            // --- 0. CEK STOK DULU TANPA MENGURANGI ---
            $kekurangan = [];

            foreach ($resep->details as $detail) {
                $obat = $detail->obat;

                if (! $obat) {
                    throw new \RuntimeException('Obat pada salah satu detail resep tidak ditemukan.');
                }

                if ($obat->stok < $detail->jumlah) {
                    $kekurangan[] = sprintf(
                        '%s (kode %s) — stok %d, diminta %d',
                        $obat->nama_obat,
                        $obat->kode_obat,
                        $obat->stok,
                        $detail->jumlah
                    );
                }
            }

            if (! empty($kekurangan)) {
                // gabungkan jadi 1 pesan yang enak dibaca di view
                $message = "Stok obat tidak mencukupi:\n- " . implode("\n- ", $kekurangan);

                 throw new StokTidakCukupException($message);
            }

            // --- 1. Header penjualan ---
            $totalHarga = 0;

            $penjualan = Penjualan::create([
                'nomor_transaksi' => Penjualan::generateNomorTransaksi(),
                'resep_id'        => $resep->id,
                'apoteker_id'     => $apoteker->id,
                'total_harga'     => 0, // di-update setelah loop
                'status'          => 'pending', // misal kamu pakai kolom status
            ]);

            // --- 2. Detail penjualan (copy dari detail resep) ---
            foreach ($resep->details as $detail) {
                $obat = $detail->obat;

                $hargaSatuan = $obat->harga_jual ?? 0;
                $subtotal    = $hargaSatuan * $detail->jumlah;

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'obat_id'      => $obat->id,
                    'jumlah'       => $detail->jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal'     => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            // --- 3. Update total di header ---
            $penjualan->update([
                'total_harga' => $totalHarga,
            ]);

            // --- 4. Ubah status resep jadi processed ---
            $resep->update([
                'status' => 'processed',
            ]);

            return $penjualan;
        });
    }

}
