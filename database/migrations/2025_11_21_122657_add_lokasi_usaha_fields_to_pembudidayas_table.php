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
        Schema::table('pembudidayas', function (Blueprint $table) {
            if (!Schema::hasColumn('pembudidayas', 'nama_kelompok')) {
                $table->string('nama_kelompok')->nullable()->after('nama_usaha');
            }
            if (!Schema::hasColumn('pembudidayas', 'kecamatan_usaha')) {
                $table->unsignedBigInteger('kecamatan_usaha')->nullable()->after('alamat_usaha');
                $table->foreign('kecamatan_usaha')->references('id_kecamatan')->on('master_kecamatans')->onDelete('set null');
            }
            if (!Schema::hasColumn('pembudidayas', 'desa_usaha')) {
                $table->unsignedBigInteger('desa_usaha')->nullable()->after('kecamatan_usaha');
                $table->foreign('desa_usaha')->references('id_desa')->on('master_desas')->onDelete('set null');
            }
            if (!Schema::hasColumn('pembudidayas', 'alamat_lengkap_usaha')) {
                $table->text('alamat_lengkap_usaha')->nullable()->after('desa_usaha');
            }
            if (!Schema::hasColumn('pembudidayas', 'latitude_usaha')) {
                $table->double('latitude_usaha')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('pembudidayas', 'longitude_usaha')) {
                $table->double('longitude_usaha')->nullable()->after('latitude_usaha');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembudidayas', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_usaha']);
            $table->dropForeign(['desa_usaha']);
            $table->dropColumn([
                'nama_kelompok',
                'kecamatan_usaha',
                'desa_usaha',
                'alamat_lengkap_usaha',
                'latitude_usaha',
                'longitude_usaha'
            ]);
        });
    }
};
