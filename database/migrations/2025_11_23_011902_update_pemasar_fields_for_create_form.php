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
            // Tambah field nama_kelompok setelah nama_usaha
            $table->string('nama_kelompok')->nullable()->after('nama_usaha');
            
            // Hapus field foto_kusuka dan foto_nib
            $table->dropColumn(['foto_kusuka', 'foto_nib']);
            
            // Tambah field foto_npwp, foto_izin_usaha, foto_produk
            $table->string('foto_npwp')->nullable();
            $table->string('foto_izin_usaha')->nullable();
            $table->string('foto_produk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            // Hapus field yang ditambahkan
            $table->dropColumn(['nama_kelompok', 'foto_npwp', 'foto_izin_usaha', 'foto_produk']);
            
            // Kembalikan field yang dihapus
            $table->string('foto_kusuka')->nullable();
            $table->string('foto_nib')->nullable();
        });
    }
};
