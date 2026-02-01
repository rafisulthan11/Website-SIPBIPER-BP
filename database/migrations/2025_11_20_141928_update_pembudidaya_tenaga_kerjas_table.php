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
        Schema::table('pembudidaya_tenaga_kerjas', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['jumlah_tetap', 'jumlah_tidak_tetap', 'keluarga_wni', 'keluarga_wna']);
            
            // Add new columns for detailed structure
            $table->integer('wni_laki_tetap')->default(0)->after('id_pembudidaya');
            $table->integer('wni_laki_tidak_tetap')->default(0);
            $table->integer('wni_laki_keluarga')->default(0);
            $table->integer('wni_perempuan_tetap')->default(0);
            $table->integer('wni_perempuan_tidak_tetap')->default(0);
            $table->integer('wni_perempuan_keluarga')->default(0);
            $table->integer('wna_laki_tetap')->default(0);
            $table->integer('wna_laki_tidak_tetap')->default(0);
            $table->integer('wna_laki_keluarga')->default(0);
            $table->integer('wna_perempuan_tetap')->default(0);
            $table->integer('wna_perempuan_tidak_tetap')->default(0);
            $table->integer('wna_perempuan_keluarga')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembudidaya_tenaga_kerjas', function (Blueprint $table) {
            // Restore old columns
            $table->integer('jumlah_tetap')->default(0);
            $table->integer('jumlah_tidak_tetap')->default(0);
            $table->integer('keluarga_wni')->default(0);
            $table->integer('keluarga_wna')->default(0);
            
            // Drop new columns
            $table->dropColumn([
                'wni_laki_tetap', 'wni_laki_tidak_tetap', 'wni_laki_keluarga',
                'wni_perempuan_tetap', 'wni_perempuan_tidak_tetap', 'wni_perempuan_keluarga',
                'wna_laki_tetap', 'wna_laki_tidak_tetap', 'wna_laki_keluarga',
                'wna_perempuan_tetap', 'wna_perempuan_tidak_tetap', 'wna_perempuan_keluarga',
            ]);
        });
    }
};
