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
        Schema::create('pengolahs', function (Blueprint $table) {
            $table->id('id_pengolah');
            $table->string('nik_pengolah')->unique()->nullable();
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
            $table->foreignId('id_kecamatan')->constrained('master_kecamatans', 'id_kecamatan');
            $table->foreignId('id_desa')->constrained('master_desas', 'id_desa');
            $table->string('jenis_kegiatan_usaha')->nullable();
            $table->string('jenis_pengolahan')->nullable();
            $table->string('nama_usaha')->nullable();
            $table->string('npwp_usaha')->nullable();
            $table->text('alamat_usaha')->nullable();
            $table->string('telp_usaha')->nullable();
            $table->string('email_usaha')->nullable();
            $table->string('skala_usaha')->nullable();
            $table->string('status_usaha')->nullable();
            $table->year('tahun_mulai_usaha')->nullable();
            $table->string('kontak')->nullable();
            $table->string('komoditas')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengolahs');
    }
};
