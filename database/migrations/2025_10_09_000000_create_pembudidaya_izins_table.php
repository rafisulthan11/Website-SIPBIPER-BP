<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pembudidaya_izins')) {
            Schema::create('pembudidaya_izins', function (Blueprint $table) {
                $table->id('id_izin');
                $table->foreignId('id_pembudidaya')->constrained('pembudidayas', 'id_pembudidaya')->onDelete('cascade');
                $table->string('nib')->nullable();
                $table->string('npwp')->nullable();
                $table->string('kusuka')->nullable();
                $table->string('pengesahan_menkumham')->nullable();
                $table->string('cbib')->nullable();
                $table->string('skai')->nullable();
                $table->string('surat_ijin_pembudidayaan_ikan')->nullable();
                $table->string('akta_pendirian_usaha')->nullable();
                $table->string('imb')->nullable();
                $table->string('sup_perikanan')->nullable();
                $table->string('sup_perdagangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembudidaya_izins');
    }
};
