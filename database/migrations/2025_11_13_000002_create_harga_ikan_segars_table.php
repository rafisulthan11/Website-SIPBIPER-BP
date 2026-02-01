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
        Schema::create('harga_ikan_segars', function (Blueprint $table) {
            $table->id('id_harga');
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_desa');
            $table->date('tanggal_input');
            $table->string('jenis_ikan', 100);
            $table->string('ukuran', 50)->nullable();
            $table->decimal('harga_produsen', 15, 2)->nullable();
            $table->decimal('harga_konsumen', 15, 2)->nullable();
            $table->string('satuan', 20);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('master_kecamatans')->onDelete('cascade');
            $table->foreign('id_desa')->references('id_desa')->on('master_desas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_ikan_segars');
    }
};
