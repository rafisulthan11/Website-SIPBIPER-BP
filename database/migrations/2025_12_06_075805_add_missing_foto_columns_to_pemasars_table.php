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
            // Cek dan tambahkan kolom jika belum ada
            if (!Schema::hasColumn('pemasars', 'foto_ktp')) {
                $table->string('foto_ktp')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_sertifikat')) {
                $table->string('foto_sertifikat')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_cpib_cbib')) {
                $table->string('foto_cpib_cbib')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_unit_usaha')) {
                $table->string('foto_unit_usaha')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_kusuka')) {
                $table->string('foto_kusuka')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_nib')) {
                $table->string('foto_nib')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $columns = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pemasars', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
