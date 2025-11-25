<?php

namespace App\Services;

use App\Models\Resep;
use App\Models\User;
use App\Models\Penjualan;
use Carbon\Carbon;

class PharmacistDashboardService
{
    /**
     * Ambil data untuk dashboard apoteker.
     */
    public function getSummary(User $user): array
    {
        $today = Carbon::today();

        // 1. Resep yang perlu disiapkan:
        //    resep sudah completed oleh dokter, belum ada penjualan
        $prescriptions_to_prepare = Resep::where('status', 'completed')
            ->whereDoesntHave('penjualan')
            ->count();

        // 2. Resep siap diambil:
        //    contoh asumsi: status 'ready' (bisa kamu sesuaikan nanti)
        $prescriptions_ready = Resep::where('status', 'ready')
            ->count();

        // 3. Daftar resep menunggu apotek (detail list)
        $daftar_resep_menunggu = Resep::with('pasien')
            ->where('status', 'completed')
            ->whereDoesntHave('penjualan')
            ->latest()
            ->take(5)
            ->get();

        // 4. Penjualan hari ini
        $sales_today_count = Penjualan::whereDate('created_at', $today)->where('status', 'paid')->count();
        $sales_today_total = Penjualan::whereDate('created_at', $today)->where('status', 'paid')->sum('total_harga');

        // 5. Penjualan terbaru
        $penjualan_terbaru = Penjualan::with('resep.pasien')
            ->latest()
            ->where('status', 'paid')
            ->take(5)
            ->get();

        return compact(
            'prescriptions_to_prepare',
            'prescriptions_ready',
            'daftar_resep_menunggu',
            'sales_today_count',
            'sales_today_total',
            'penjualan_terbaru',
        );
    }
}
