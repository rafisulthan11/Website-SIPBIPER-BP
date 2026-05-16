<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            if (!Schema::hasColumn('harga_ikan_segars', 'nik_pedagang')) {
                $table->string('nik_pedagang', 16)->nullable()->after('nama_pedagang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('harga_ikan_segars', function (Blueprint $table) {
            if (Schema::hasColumn('harga_ikan_segars', 'nik_pedagang')) {
                $table->dropColumn('nik_pedagang');
            }
        });
    }
};