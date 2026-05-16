<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('harga_ikan_segars') && Schema::hasColumn('harga_ikan_segars', 'nik_pedagang')) {
            DB::statement('ALTER TABLE harga_ikan_segars MODIFY nik_pedagang VARCHAR(16) NULL AFTER nama_pedagang');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('harga_ikan_segars') && Schema::hasColumn('harga_ikan_segars', 'nik_pedagang')) {
            DB::statement('ALTER TABLE harga_ikan_segars MODIFY nik_pedagang VARCHAR(6) NULL AFTER nama_pedagang');
        }
    }
};