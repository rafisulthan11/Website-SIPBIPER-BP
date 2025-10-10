<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pembudidaya_investasis')) {
            Schema::table('pembudidaya_investasis', function (Blueprint $table) {
                $addDecimal = function ($name, $precision = 15, $scale = 2) use ($table) {
                    if (!Schema::hasColumn('pembudidaya_investasis', $name)) {
                        $table->decimal($name, $precision, $scale)->nullable()->after('id_pembudidaya');
                    }
                };

                if (!Schema::hasColumn('pembudidaya_investasis', 'id_pembudidaya')) {
                    $table->foreignId('id_pembudidaya')->nullable()->after('id_investasi');
                }

                $addDecimal('nilai_asset');
                $addDecimal('laba_ditanam');
                $addDecimal('sewa');

                if (!Schema::hasColumn('pembudidaya_investasis', 'pinjaman')) {
                    $table->boolean('pinjaman')->nullable()->after('sewa');
                }

                $addDecimal('modal_sendiri');

                if (!Schema::hasColumn('pembudidaya_investasis', 'lahan_status')) {
                    $table->text('lahan_status')->nullable()->after('modal_sendiri');
                }

                $addDecimal('luas_m2', 12, 2);
                $addDecimal('nilai_bangunan');

                if (!Schema::hasColumn('pembudidaya_investasis', 'bangunan')) {
                    $table->string('bangunan')->nullable()->after('nilai_bangunan');
                }
                if (!Schema::hasColumn('pembudidaya_investasis', 'sertifikat')) {
                    $table->string('sertifikat')->nullable()->after('bangunan');
                }
            });
        }
    }

    public function down(): void
    {
        // No-op safe down; we won't drop columns to avoid data loss.
    }
};
