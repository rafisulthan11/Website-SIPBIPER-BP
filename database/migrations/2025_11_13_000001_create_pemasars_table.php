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
        Schema::create('pemasars', function (Blueprint $table) {
            $table->id('id_pemasar');
            $table->string('nik_pemasar', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('email')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->integer('jumlah_tanggungan')->nullable();
            $table->text('alamat')->nullable();
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_desa');
            $table->string('jenis_kegiatan_usaha')->nullable();
            $table->string('jenis_pemasaran')->nullable();
            $table->string('nama_usaha')->nullable();
            $table->string('npwp_usaha')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->string('telp_usaha')->nullable();
            $table->string('email_usaha')->nullable();
            $table->string('skala_usaha')->nullable();
            $table->string('status_usaha')->nullable();
            $table->integer('tahun_mulai_usaha')->nullable();
            $table->string('kontak')->nullable();
            $table->string('komoditas')->nullable();
            $table->string('wilayah_pemasaran')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('pemasars');
    }
};
