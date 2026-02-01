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
        Schema::table('pemasars', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kecamatan_usaha')->nullable()->after('id_desa');
            $table->unsignedBigInteger('id_desa_usaha')->nullable()->after('id_kecamatan_usaha');
            
            // Foreign keys
            $table->foreign('id_kecamatan_usaha')->references('id_kecamatan')->on('master_kecamatans')->onDelete('cascade');
            $table->foreign('id_desa_usaha')->references('id_desa')->on('master_desas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropForeign(['id_kecamatan_usaha']);
            $table->dropForeign(['id_desa_usaha']);
            $table->dropColumn(['id_kecamatan_usaha', 'id_desa_usaha']);
        });
    }
};
