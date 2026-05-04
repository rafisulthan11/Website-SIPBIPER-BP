<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pasar', 'latitude') || Schema::hasColumn('pasar', 'longitude')) {
            DB::table('pasar')->update([
                'latitude' => null,
                'longitude' => null,
            ]);
        }

        Schema::table('pasar', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('pasar', 'latitude')) {
                $columns[] = 'latitude';
            }
            if (Schema::hasColumn('pasar', 'longitude')) {
                $columns[] = 'longitude';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('pasar', function (Blueprint $table) {
            if (!Schema::hasColumn('pasar', 'latitude')) {
                $table->string('latitude', 255)->nullable();
            }
            if (!Schema::hasColumn('pasar', 'longitude')) {
                $table->string('longitude', 255)->nullable();
            }
        });
    }
};
