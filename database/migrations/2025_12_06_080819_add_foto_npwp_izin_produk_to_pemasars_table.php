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
            if (!Schema::hasColumn('pemasars', 'foto_npwp')) {
                $table->string('foto_npwp')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_izin_usaha')) {
                $table->string('foto_izin_usaha')->nullable();
            }
            if (!Schema::hasColumn('pemasars', 'foto_produk')) {
                $table->string('foto_produk')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $columns = ['foto_npwp', 'foto_izin_usaha', 'foto_produk'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pemasars', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
