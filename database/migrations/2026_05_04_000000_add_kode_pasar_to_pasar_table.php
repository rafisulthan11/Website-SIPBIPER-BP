<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            if (!Schema::hasColumn('pasar', 'kode_pasar')) {
                $table->string('kode_pasar', 50)->nullable()->after('nama_pasar');
                $table->unique('kode_pasar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            if (Schema::hasColumn('pasar', 'kode_pasar')) {
                $table->dropUnique(['kode_pasar']);
                $table->dropColumn('kode_pasar');
            }
        });
    }
};
