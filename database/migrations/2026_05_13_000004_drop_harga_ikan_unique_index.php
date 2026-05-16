<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('harga_ikan_segars')) {
            Schema::table('harga_ikan_segars', function (Blueprint $table) {
                $table->dropUnique('harga_ikan_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('harga_ikan_segars')) {
            Schema::table('harga_ikan_segars', function (Blueprint $table) {
                $table->unique(['tahun_pendataan', 'tanggal_input', 'nik_pedagang', 'nama_pasar'], 'harga_ikan_unique');
            });
        }
    }
};