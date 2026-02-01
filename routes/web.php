<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PembudidayaController;
use App\Http\Controllers\PengolahController;
use App\Http\Controllers\PemasarController;
use App\Http\Controllers\HargaIkanSegarController;
use App\Http\Controllers\KomoditasController;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaLokasiController;


Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

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

    // API Routes for dependent dropdowns
    Route::get('/api/desa-by-kecamatan/{id_kecamatan}', [PembudidayaController::class, 'getDesaByKecamatan'])->name('api.desa.by.kecamatan');
    Route::get('/api/pasar-by-desa/{id_desa}', [HargaIkanSegarController::class, 'getPasarByDesa'])->name('api.pasar.by.desa');
    
    Route::resource('pembudidaya', PembudidayaController::class);
    Route::resource('pengolah', PengolahController::class);
    Route::resource('pemasar', PemasarController::class);
    Route::resource('harga-ikan-segar', HargaIkanSegarController::class);
    Route::resource('komoditas', KomoditasController::class);
    Route::resource('pasar', PasarController::class);
    
    // Peta Lokasi Routes
    Route::get('/peta-lokasi', [PetaLokasiController::class, 'index'])->name('peta-lokasi.index');

    // Grafik
    Route::get('/grafik/pelaku-usaha', [\App\Http\Controllers\GrafikController::class, 'pelakuUsaha'])->name('grafik.pelaku.usaha');
    Route::get('/grafik/harga-ikan-segar', [\App\Http\Controllers\GrafikController::class, 'hargaIkanSegar'])->name('grafik.harga.ikan.segar');
    Route::get('/grafik/pendataan-wilayah', [\App\Http\Controllers\GrafikController::class, 'pendataanWilayah'])->name('grafik.pendataan.wilayah');

    // Laporan - rekapitulasi pembudidaya
    Route::get('/laporan/rekapitulasi/pembudidaya', [\App\Http\Controllers\LaporanController::class, 'rekapitulasiPembudidaya'])
        ->name('laporan.rekapitulasi.pembudidaya');
    Route::get('/laporan/rekapitulasi/pembudidaya/export', [\App\Http\Controllers\LaporanController::class, 'exportPembudidaya'])
        ->name('laporan.rekapitulasi.pembudidaya.export');
    Route::get('/laporan/rekapitulasi/pembudidaya/pdf/{id}', [\App\Http\Controllers\LaporanController::class, 'pdfPembudidaya'])
        ->name('laporan.rekapitulasi.pembudidaya.pdf');
    
    Route::get('/laporan/rekapitulasi/pengolah', [\App\Http\Controllers\LaporanController::class, 'rekapitulasiPengolah'])
        ->name('laporan.rekapitulasi.pengolah');
    Route::get('/laporan/rekapitulasi/pengolah/export', [\App\Http\Controllers\LaporanController::class, 'exportPengolah'])
        ->name('laporan.rekapitulasi.pengolah.export');
    Route::get('/laporan/rekapitulasi/pengolah/pdf/{id}', [\App\Http\Controllers\LaporanController::class, 'pdfPengolah'])
        ->name('laporan.rekapitulasi.pengolah.pdf');
    
    Route::get('/laporan/rekapitulasi/pemasar', [\App\Http\Controllers\LaporanController::class, 'rekapitulasiPemasar'])
        ->name('laporan.rekapitulasi.pemasar');
    Route::get('/laporan/rekapitulasi/pemasar/export', [\App\Http\Controllers\LaporanController::class, 'exportPemasar'])
        ->name('laporan.rekapitulasi.pemasar.export');
    Route::get('/laporan/rekapitulasi/pemasar/pdf/{id}', [\App\Http\Controllers\LaporanController::class, 'pdfPemasar'])
        ->name('laporan.rekapitulasi.pemasar.pdf');
    
    Route::get('/laporan/harga-ikan-segar', [\App\Http\Controllers\LaporanController::class, 'rekapHargaIkanSegar'])
        ->name('laporan.harga.ikan.segar');
    Route::get('/laporan/harga-ikan-segar/export', [\App\Http\Controllers\LaporanController::class, 'exportHargaIkanSegar'])
        ->name('laporan.harga.ikan.segar.export');
    Route::get('/laporan/harga-ikan-segar/pdf/{id}', [\App\Http\Controllers\LaporanController::class, 'pdfHargaIkanSegar'])
        ->name('laporan.harga.ikan.segar.pdf');
});

require __DIR__.'/auth.php';
