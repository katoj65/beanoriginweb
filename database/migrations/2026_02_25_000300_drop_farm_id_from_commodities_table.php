<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('commodities', 'farm_id')) {
            return;
        }

        Schema::table('commodities', function (Blueprint $table) {
            $table->dropForeign(['farm_id']);
            $table->dropColumn('farm_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('commodities', 'farm_id')) {
            return;
        }

        Schema::table('commodities', function (Blueprint $table) {
            $table->foreignId('farm_id')->nullable()->after('cooperative_id');
            $table->foreign('farm_id')->references('id')->on('farms')->nullOnDelete();
        });
    }
};

