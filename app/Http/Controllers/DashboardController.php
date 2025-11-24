<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'dokter') {
            return $this->dokterDashboard($user);
        }

        // sementara untuk role selain dokter, pakai view dashboard lama
        return view('dashboard');
    }

    protected function dokterDashboard($user)
    {
        $today        = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // 1. total resep saya hari ini
        $total_resep_saya_hari_ini = Resep::where('dokter_id', $user->id)
            ->whereDate('created_at', $today)
            ->count();

        // 2. pasien unik bulan ini
        $total_pasien_unik_bulan_ini = Resep::where('dokter_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->distinct('pasien_id')
            ->count('pasien_id');

        // 3. resep menunggu apotek (sudah completed oleh dokter, belum ada penjualan)
        $total_resep_menunggu_apotek = Resep::where('dokter_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('penjualan')
            ->count();

        // 4. resep draft (belum dilengkapi / belum dikirim)
        $total_resep_draft = Resep::where('dokter_id', $user->id)
            ->where('status', 'draft')
            ->count();

        // daftar resep terbaru dokter
        $daftar_resep_terbaru = Resep::with('pasien')
            ->where('dokter_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // daftar resep menunggu apotek (detail list)
        $daftar_resep_menunggu_apotek = Resep::with('pasien')
            ->where('dokter_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('penjualan')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard-dokter', compact(
            'total_resep_saya_hari_ini',
            'total_pasien_unik_bulan_ini',
            'total_resep_menunggu_apotek',
            'total_resep_draft',
            'daftar_resep_terbaru',
            'daftar_resep_menunggu_apotek'
        ));
    }
}
