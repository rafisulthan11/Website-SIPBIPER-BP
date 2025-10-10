<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pembudidaya_izins')) {
            Schema::table('pembudidaya_izins', function (Blueprint $table) {
                if (!Schema::hasColumn('pembudidaya_izins', 'id_pembudidaya')) {
                    $table->foreignId('id_pembudidaya')->nullable()->after('id_izin');
                }

                $addStr = function (string $name) use ($table) {
                    if (!Schema::hasColumn('pembudidaya_izins', $name)) {
                        $table->string($name)->nullable()->after('id_pembudidaya');
                    }
                };

                $addStr('nib');
                $addStr('npwp');
                $addStr('kusuka');
                $addStr('pengesahan_menkumham');
                $addStr('cbib');
                $addStr('skai');
                $addStr('surat_ijin_pembudidayaan_ikan');
                $addStr('akta_pendirian_usaha');
                $addStr('imb');
                $addStr('sup_perikanan');
                $addStr('sup_perdagangan');

                if (!Schema::hasColumn('pembudidaya_izins', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('pembudidaya_izins', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // No destructive down to avoid data loss
    }
};
