<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('commodities', 'density_percentage')) {
            Schema::table('commodities', function (Blueprint $table) {
                $table->integer('density_percentage')->nullable()->after('ripe_percentage');
            });
        }

        if (Schema::hasColumn('commodities', 'desity_percentage') && Schema::hasColumn('commodities', 'density_percentage')) {
            DB::statement('UPDATE commodities SET density_percentage = desity_percentage WHERE density_percentage IS NULL');

            Schema::table('commodities', function (Blueprint $table) {
                $table->dropColumn('desity_percentage');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('commodities', 'desity_percentage')) {
            Schema::table('commodities', function (Blueprint $table) {
                $table->integer('desity_percentage')->nullable()->after('ripe_percentage');
            });
        }

        if (Schema::hasColumn('commodities', 'density_percentage') && Schema::hasColumn('commodities', 'desity_percentage')) {
            DB::statement('UPDATE commodities SET desity_percentage = density_percentage WHERE desity_percentage IS NULL');
        }
    }
};
