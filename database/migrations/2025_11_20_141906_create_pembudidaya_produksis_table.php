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
        Schema::create('pembudidaya_produksis', function (Blueprint $table) {
            $table->id('id_produksi');
            $table->foreignId('id_pembudidaya')->constrained('pembudidayas', 'id_pembudidaya')->onDelete('cascade');
            $table->decimal('total_luas_kolam', 10, 2)->nullable();
            $table->decimal('total_produksi', 10, 2)->nullable();
            $table->string('satuan_produksi')->nullable();
            $table->decimal('harga_per_satuan', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembudidaya_produksis');
    }
};
