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
                // Drop legacy columns if they exist, since they are not needed
                foreach (['jenis_izin','nomor_izin','tanggal_terbit'] as $col) {
                    if (Schema::hasColumn('pembudidaya_izins', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        // Down is a no-op to avoid reintroducing legacy columns
    }
};
