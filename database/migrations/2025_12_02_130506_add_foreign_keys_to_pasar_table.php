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
        Schema::table('pasar', function (Blueprint $table) {
            // Tambahkan kolom id_kecamatan dan id_desa
            $table->unsignedBigInteger('id_kecamatan')->nullable()->after('id_pasar');
            $table->unsignedBigInteger('id_desa')->nullable()->after('id_kecamatan');
            
            // Tambahkan foreign key constraints
            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('master_kecamatans')
                  ->onDelete('set null');
                  
            $table->foreign('id_desa')
                  ->references('id_desa')
                  ->on('master_desas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            $table->dropForeign(['id_kecamatan']);
            $table->dropForeign(['id_desa']);
            $table->dropColumn(['id_kecamatan', 'id_desa']);
        });
    }
};
