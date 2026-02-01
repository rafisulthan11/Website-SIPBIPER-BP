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
        Schema::create('pembudidaya_ikans', function (Blueprint $table) {
            $table->id('id_ikan');
            $table->foreignId('id_pembudidaya')->constrained('pembudidayas', 'id_pembudidaya')->onDelete('cascade');
            $table->string('jenis_ikan');
            $table->string('jenis_indukan')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('asal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembudidaya_ikans');
    }
};
