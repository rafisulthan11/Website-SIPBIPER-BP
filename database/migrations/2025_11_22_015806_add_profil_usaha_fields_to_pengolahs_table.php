<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengolahs', function (Blueprint $table) {
            $table->string('nama_kelompok')->nullable()->after('nama_usaha');
            $table->foreignId('id_kecamatan_usaha')->nullable()->after('nama_kelompok')->constrained('master_kecamatans', 'id_kecamatan');
            $table->foreignId('id_desa_usaha')->nullable()->after('id_kecamatan_usaha')->constrained('master_desas', 'id_desa');
            $table->json('produksi_data')->nullable()->after('longitude');
            $table->json('tenaga_kerja_data')->nullable()->after('produksi_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengolahs', function (Blueprint $table) {
            $table->dropForeign(['id_kecamatan_usaha']);
            $table->dropForeign(['id_desa_usaha']);
            $table->dropColumn(['nama_kelompok', 'id_kecamatan_usaha', 'id_desa_usaha', 'produksi_data', 'tenaga_kerja_data']);
        });
    }
};
