<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PembudidayaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaLokasiController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('is.admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    Route::resource('pembudidaya', PembudidayaController::class);
    
    // Peta Lokasi Routes
    Route::get('/peta-lokasi', [PetaLokasiController::class, 'index'])->name('peta-lokasi.index');
    Route::get('/peta-lokasi/pengolah', [PetaLokasiController::class, 'pengolah'])->name('peta-lokasi.pengolah');
    Route::get('/peta-lokasi/pemasar', [PetaLokasiController::class, 'pemasar'])->name('peta-lokasi.pemasar');
});

require __DIR__.'/auth.php';
