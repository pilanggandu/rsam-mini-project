<?php

namespace App\Services;

use App\Models\Resep;
use App\Models\User;
use Carbon\Carbon;

class DoctorDashboardService
{
    /**
     * Ambil semua data yang dibutuhkan dashboard dokter.
     */
    public function getSummary(User $user): array
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

        // 4. resep draft (belum dikirim)
        $total_resep_draft = Resep::where('dokter_id', $user->id)
            ->where('status', 'draft')
            ->count();

        // daftar resep terbaru dokter
        $daftar_resep_terbaru = Resep::with('pasien')
            ->where('dokter_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // daftar resep menunggu apotek
        $daftar_resep_menunggu_apotek = Resep::with('pasien')
            ->where('dokter_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('penjualan')
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'total_resep_saya_hari_ini',
            'total_pasien_unik_bulan_ini',
            'total_resep_menunggu_apotek',
            'total_resep_draft',
            'daftar_resep_terbaru',
            'daftar_resep_menunggu_apotek'
        );
    }
}
