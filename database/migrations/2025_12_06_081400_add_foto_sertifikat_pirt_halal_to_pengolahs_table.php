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
        Schema::table('pengolahs', function (Blueprint $table) {
            if (!Schema::hasColumn('pengolahs', 'foto_sertifikat_pirt')) {
                $table->string('foto_sertifikat_pirt')->nullable();
            }
            if (!Schema::hasColumn('pengolahs', 'foto_sertifikat_halal')) {
                $table->string('foto_sertifikat_halal')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengolahs', function (Blueprint $table) {
            $columns = ['foto_sertifikat_pirt', 'foto_sertifikat_halal'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pengolahs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
