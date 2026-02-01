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
            $table->string('foto_ktp')->nullable();
            $table->string('foto_sertifikat')->nullable();
            $table->string('foto_cpib_cbib')->nullable();
            $table->string('foto_unit_usaha')->nullable();
            $table->string('foto_kusuka')->nullable();
            $table->string('foto_nib')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengolahs', function (Blueprint $table) {
            $table->dropColumn([
                'foto_ktp',
                'foto_sertifikat',
                'foto_cpib_cbib',
                'foto_unit_usaha',
                'foto_kusuka',
                'foto_nib',
            ]);
        });
    }
};
