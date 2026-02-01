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
            // Investasi - Modal Tetap
            $table->decimal('investasi_tanah', 15, 2)->nullable()->after('longitude');
            $table->decimal('investasi_gedung', 15, 2)->nullable();
            $table->decimal('investasi_mesin_peralatan', 15, 2)->nullable();
            $table->decimal('investasi_kendaraan', 15, 2)->nullable();
            $table->decimal('investasi_lain_lain', 15, 2)->nullable();
            $table->decimal('investasi_sub_jumlah', 15, 2)->nullable();
            
            // Investasi - Modal Kerja
            $table->decimal('modal_kerja_1_bulan', 15, 2)->nullable();
            $table->decimal('modal_kerja_sub_jumlah', 15, 2)->nullable();
            
            // Sumber Pembiayaan
            $table->decimal('modal_sendiri', 15, 2)->nullable();
            $table->decimal('laba_ditanam', 15, 2)->nullable();
            $table->decimal('modal_pinjam', 15, 2)->nullable();
            
            // Sertifikat Lahan
            $table->json('sertifikat_lahan')->nullable();
            $table->decimal('luas_lahan', 10, 2)->nullable();
            $table->decimal('nilai_lahan', 15, 2)->nullable();
            
            // Sertifikat Bangunan
            $table->json('sertifikat_bangunan')->nullable();
            $table->decimal('luas_bangunan', 10, 2)->nullable();
            $table->decimal('nilai_bangunan', 15, 2)->nullable();
            
            // Kapasitas dan Produksi
            $table->decimal('kapasitas_terpasang_setahun', 12, 2)->nullable();
            $table->json('bulan_produksi')->nullable();
            $table->integer('jumlah_hari_produksi')->nullable();
            $table->text('distribusi_pemasaran')->nullable();
            
            // Tenaga Kerja - WNI
            $table->integer('wni_laki_tetap')->nullable();
            $table->integer('wni_laki_tidak_tetap')->nullable();
            $table->integer('wni_laki_keluarga')->nullable();
            $table->integer('wni_perempuan_tetap')->nullable();
            $table->integer('wni_perempuan_tidak_tetap')->nullable();
            $table->integer('wni_perempuan_keluarga')->nullable();
            
            // Tenaga Kerja - WNA
            $table->integer('wna_laki_tetap')->nullable();
            $table->integer('wna_laki_tidak_tetap')->nullable();
            $table->integer('wna_laki_keluarga')->nullable();
            $table->integer('wna_perempuan_tetap')->nullable();
            $table->integer('wna_perempuan_tidak_tetap')->nullable();
            $table->integer('wna_perempuan_keluarga')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropColumn([
                'investasi_tanah',
                'investasi_gedung',
                'investasi_mesin_peralatan',
                'investasi_kendaraan',
                'investasi_lain_lain',
                'investasi_sub_jumlah',
                'modal_kerja_1_bulan',
                'modal_kerja_sub_jumlah',
                'modal_sendiri',
                'laba_ditanam',
                'modal_pinjam',
                'sertifikat_lahan',
                'luas_lahan',
                'nilai_lahan',
                'sertifikat_bangunan',
                'luas_bangunan',
                'nilai_bangunan',
                'kapasitas_terpasang_setahun',
                'bulan_produksi',
                'jumlah_hari_produksi',
                'distribusi_pemasaran',
                'wni_laki_tetap',
                'wni_laki_tidak_tetap',
                'wni_laki_keluarga',
                'wni_perempuan_tetap',
                'wni_perempuan_tidak_tetap',
                'wni_perempuan_keluarga',
                'wna_laki_tetap',
                'wna_laki_tidak_tetap',
                'wna_laki_keluarga',
                'wna_perempuan_tetap',
                'wna_perempuan_tidak_tetap',
                'wna_perempuan_keluarga',
            ]);
        });
    }
};
