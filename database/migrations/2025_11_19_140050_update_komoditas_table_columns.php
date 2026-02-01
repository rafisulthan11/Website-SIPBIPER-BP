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
        Schema::table('komoditas', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['jenis', 'kategori', 'deskripsi']);
            
            // Tambah kolom baru
            $table->string('tipe')->after('nama_komoditas');
            $table->string('kode')->after('tipe');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif')->after('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komoditas', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->string('jenis')->nullable()->after('nama_komoditas');
            $table->string('kategori')->nullable()->after('jenis');
            $table->text('deskripsi')->nullable()->after('kategori');
            
            // Hapus kolom baru
            $table->dropColumn(['tipe', 'kode', 'status']);
        });
    }
};
