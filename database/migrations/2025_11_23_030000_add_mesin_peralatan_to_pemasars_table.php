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
            $table->json('mesin_peralatan')->nullable()->after('distribusi_pemasaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasars', function (Blueprint $table) {
            $table->dropColumn('mesin_peralatan');
        });
    }
};
