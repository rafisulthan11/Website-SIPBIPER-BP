<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemasars_pemasaran', function (Blueprint $table) {
            $table->id('id_pemasar_pemasaran');
            $table->unsignedBigInteger('id_pemasar');
            $table->string('komoditas')->nullable();
            $table->string('asal_ikan')->nullable();
            $table->decimal('jumlah_volume', 12, 2)->nullable();
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->decimal('kebutuhan_min', 12, 2)->nullable();
            $table->decimal('kebutuhan_max', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('id_pemasar')
                ->references('id_pemasar')
                ->on('pemasars')
                ->onDelete('cascade');
        });

        $this->migratePemasaranData();

        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pemasaran',
                'komoditas',
                'wilayah_pemasaran',
                'kapasitas_terpasang_setahun',
                'jumlah_hari_produksi',
                'biaya_produksi',
                'foto_kusuka',
                'foto_nib',
                'harga_jual_produksi',
                'data_pemasaran',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->string('jenis_pemasaran')->nullable();
            $table->string('komoditas')->nullable();
            $table->string('wilayah_pemasaran')->nullable();
            $table->decimal('kapasitas_terpasang_setahun', 12, 2)->nullable();
            $table->integer('jumlah_hari_produksi')->nullable();
            $table->decimal('biaya_produksi', 15, 2)->nullable();
            $table->decimal('harga_jual_produksi', 15, 2)->nullable();
            $table->json('data_pemasaran')->nullable();
            $table->string('foto_kusuka')->nullable();
            $table->string('foto_nib')->nullable();
        });

        Schema::dropIfExists('pemasars_pemasaran');
    }

    private function migratePemasaranData(): void
    {
        if (!Schema::hasColumn('pemasars', 'data_pemasaran')) {
            return;
        }

        $pemasars = DB::table('pemasars')
            ->select('id_pemasar', 'data_pemasaran')
            ->whereNotNull('data_pemasaran')
            ->get();

        foreach ($pemasars as $pemasar) {
            $decoded = is_string($pemasar->data_pemasaran)
                ? json_decode($pemasar->data_pemasaran, true)
                : $pemasar->data_pemasaran;

            if (!is_array($decoded)) {
                continue;
            }

            $rows = [];
            foreach ($decoded as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $rows[] = [
                    'id_pemasar' => $pemasar->id_pemasar,
                    'komoditas' => $row['komoditas'] ?? $row['jenis_ikan'] ?? null,
                    'asal_ikan' => $row['asal_ikan'] ?? null,
                    'jumlah_volume' => isset($row['jumlah_volume']) ? (float) $row['jumlah_volume'] : null,
                    'harga_beli' => isset($row['harga_beli']) ? (float) $row['harga_beli'] : null,
                    'harga_jual' => isset($row['harga_jual']) ? (float) $row['harga_jual'] : null,
                    'kebutuhan_min' => isset($row['kebutuhan_min']) ? (float) $row['kebutuhan_min'] : null,
                    'kebutuhan_max' => isset($row['kebutuhan_max']) ? (float) $row['kebutuhan_max'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($rows)) {
                DB::table('pemasars_pemasaran')->insert($rows);
            }
        }
    }
};
