<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemasars_pemasaran', function (Blueprint $table) {
            $table->dropColumn(['kebutuhan_min', 'kebutuhan_max']);
        });
    }

    public function down(): void
    {
        Schema::table('pemasars_pemasaran', function (Blueprint $table) {
            $table->decimal('kebutuhan_min', 12, 2)->nullable();
            $table->decimal('kebutuhan_max', 12, 2)->nullable();
        });
    }
};
