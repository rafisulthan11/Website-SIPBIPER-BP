<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasars_pemasaran', function (Blueprint $table) {
            $table->integer('section_index')->nullable()->after('id_pemasar');
            $table->decimal('kapasitas_terpasang', 12, 2)->nullable()->after('section_index');
            $table->decimal('hasil_produksi_kg', 12, 2)->nullable()->after('kapasitas_terpasang');
            $table->decimal('hasil_produksi_rp', 15, 2)->nullable()->after('hasil_produksi_kg');
            $table->json('bulan_produksi')->nullable()->after('hasil_produksi_rp');
            $table->text('distribusi_pemasaran')->nullable()->after('bulan_produksi');
        });
    }

    public function down(): void
    {
        Schema::table('pemasars_pemasaran', function (Blueprint $table) {
            $table->dropColumn([
                'section_index',
                'kapasitas_terpasang',
                'hasil_produksi_kg',
                'hasil_produksi_rp',
                'bulan_produksi',
                'distribusi_pemasaran',
            ]);
        });
    }
};
