<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::group(['middleware' => 'auth', 'verified'], function () {
//     Route::get('/dashboard', App\Http\Controllers\DashboardController::class)->name('dashboard');
// });
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)
        ->name('dashboard');

    Route::resource('resep', \App\Http\Controllers\ResepController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
