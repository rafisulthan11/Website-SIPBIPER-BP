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
        // Add composite unique index to pembudidayas: nik_pembudidaya + tahun_pendataan
        Schema::table('pembudidayas', function (Blueprint $table) {
            $table->unique(['nik_pembudidaya', 'tahun_pendataan'], 'pembudidayas_nik_tahun_unique');
        });

        // Add composite unique index to pengolahs: nik_pengolah + tahun_pendataan
        Schema::table('pengolahs', function (Blueprint $table) {
            $table->unique(['nik_pengolah', 'tahun_pendataan'], 'pengolahs_nik_tahun_unique');
        });

        // Add composite unique index to pemasars: nik_pemasar + tahun_pendataan
        Schema::table('pemasars', function (Blueprint $table) {
            $table->unique(['nik_pemasar', 'tahun_pendataan'], 'pemasars_nik_tahun_unique');
        });

        // Add composite unique index to harga_ikan_segars
        // Combination: tahun_pendataan + tanggal_input + nik_pedagang + nama_pasar
        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            $table->unique(
                ['tahun_pendataan', 'tanggal_input', 'nik_pedagang', 'nama_pasar'], 
                'harga_ikan_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembudidayas', function (Blueprint $table) {
            $table->dropUnique('pembudidayas_nik_tahun_unique');
        });

        Schema::table('pengolahs', function (Blueprint $table) {
            $table->dropUnique('pengolahs_nik_tahun_unique');
        });

        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropUnique('pemasars_nik_tahun_unique');
        });

        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            $table->dropUnique('harga_ikan_unique');
        });
    }
};
