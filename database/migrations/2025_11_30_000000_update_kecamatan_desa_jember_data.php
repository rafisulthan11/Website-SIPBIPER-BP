<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengupdate data master kecamatan dan desa Kabupaten Jember
     */
    public function up(): void
    {
        // Jalankan seeder untuk mengupdate data kecamatan dan desa
        Artisan::call('db:seed', [
            '--class' => 'KecamatanJemberSeeder'
        ]);
        
        Artisan::call('db:seed', [
            '--class' => 'DesaJemberSeeder'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini hanya update data
    }
};
