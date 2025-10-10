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
    if (!Schema::hasTable('pembudidaya_investasis')) {
            Schema::create('pembudidaya_investasis', function (Blueprint $table) {
                $table->id('id_investasi');
        // konsisten dengan kolom relasi utama pada tabel pembudidayas
        $table->foreignId('id_pembudidaya')->constrained('pembudidayas', 'id_pembudidaya')->onDelete('cascade');
                $table->decimal('nilai_asset', 15, 2)->nullable();
                $table->decimal('laba_ditanam', 15, 2)->nullable();
                $table->decimal('sewa', 15, 2)->nullable();
                $table->boolean('pinjaman')->nullable(); // true: Ada, false: Tidak
                $table->decimal('modal_sendiri', 15, 2)->nullable();
                $table->text('lahan_status')->nullable(); // JSON encoded array: ["LHSM","SHRS","SHGB","Girik/Petok"]
                $table->decimal('luas_m2', 12, 2)->nullable();
                $table->decimal('nilai_bangunan', 15, 2)->nullable();
                $table->string('bangunan')->nullable(); // keterangan singkat bangunan
                $table->string('sertifikat')->nullable(); // IMB / NON_IMB
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembudidaya_investasis');
    }
};
