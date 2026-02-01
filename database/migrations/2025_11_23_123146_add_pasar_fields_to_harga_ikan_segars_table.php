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
        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            $table->string('nama_pasar', 100)->nullable()->after('tanggal_input');
            $table->string('nama_pedagang', 100)->nullable()->after('nama_pasar');
            $table->string('asal_ikan', 255)->nullable()->after('nama_pedagang');
            $table->decimal('kuantitas_perminggu', 15, 2)->nullable()->after('satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            $table->dropColumn(['nama_pasar', 'nama_pedagang', 'asal_ikan', 'kuantitas_perminggu']);
        });
    }
};
