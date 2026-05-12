<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'bulan_produksi',
            'distribusi_pemasaran',
            'kapasitas_terpasang',
            'hasil_produksi_kg',
            'hasil_produksi_rp',
        ];

        $existingColumns = array_values(array_filter($columns, function (string $column): bool {
            return Schema::hasColumn('pemasars', $column);
        }));

        if (count($existingColumns) === 0) {
            return;
        }

        Schema::table('pemasars', function (Blueprint $table) use ($existingColumns) {
            $table->dropColumn($existingColumns);
        });
    }

    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->json('bulan_produksi')->nullable()->after('nilai_bangunan');
            $table->text('distribusi_pemasaran')->nullable()->after('bulan_produksi');
            $table->decimal('kapasitas_terpasang', 12, 2)->nullable()->after('distribusi_pemasaran');
            $table->decimal('hasil_produksi_kg', 12, 2)->nullable()->after('kapasitas_terpasang');
            $table->decimal('hasil_produksi_rp', 15, 2)->nullable()->after('hasil_produksi_kg');
        });
    }
};
