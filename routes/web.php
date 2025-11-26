<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResepController;
use App\Models\Penjualan;
use App\Models\Resep;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $today = Carbon::today();
    $totalResepHariIni = Resep::whereDate('created_at', $today)->count();
    $totalResepBelumDiproses = Resep::whereDate('created_at', $today)
        ->where('status', 'completed')
        ->whereDoesntHave('penjualan')
        ->count();
    $totalPenjualanHariIni = Penjualan::whereDate('created_at', $today)
        ->sum('total_harga');

    return view('welcome', compact(
        'totalResepHariIni',
        'totalResepBelumDiproses',
        'totalPenjualanHariIni',
    ));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:doctor,pharmacist'])->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::resource('obat', ObatController::class);

    Route::resource('resep', ResepController::class);
    Route::post('resep/{resep}/complete', [ResepController::class, 'complete'])
        ->name('resep.complete');
    Route::post('resep/{resep}/process', [ResepController::class, 'process'])
        ->name('resep.process');
});

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('pasien', [PasienController::class, 'index'])
        ->name('pasien.index');
});

Route::middleware(['auth', 'role:pharmacist'])->group(function () {
    Route::get('penjualan', [PenjualanController::class, 'index'])
        ->name('penjualan.index');

    Route::get('penjualan/{penjualan}', [PenjualanController::class, 'show'])
        ->name('penjualan.show');

    Route::post('penjualan/{penjualan}/finalize', [PenjualanController::class, 'finalize'])
        ->name('penjualan.finalize');
});

require __DIR__.'/auth.php';
