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
            $table->string('nib')->nullable();
            $table->string('npwp_izin')->nullable();
            $table->string('kusuka')->nullable();
            $table->string('pengesahan_menkumham')->nullable();
            $table->string('tdu_php')->nullable();
            $table->string('sppl')->nullable();
            $table->string('siup_perdagangan')->nullable();
            $table->string('akta_pendiri_usaha')->nullable();
            $table->string('imb')->nullable();
            $table->string('siup_perikanan')->nullable();
            $table->string('ukl_upl')->nullable();
            $table->string('amdal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropColumn([
                'nib',
                'npwp_izin',
                'kusuka',
                'pengesahan_menkumham',
                'tdu_php',
                'sppl',
                'siup_perdagangan',
                'akta_pendiri_usaha',
                'imb',
                'siup_perikanan',
                'ukl_upl',
                'amdal',
            ]);
        });
    }
};
