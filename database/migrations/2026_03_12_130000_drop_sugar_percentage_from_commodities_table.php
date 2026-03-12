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
        if (Schema::hasColumn('commodities', 'sugar_percentage')) {
            Schema::table('commodities', function (Blueprint $table) {
                $table->dropColumn('sugar_percentage');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('commodities', 'sugar_percentage')) {
            Schema::table('commodities', function (Blueprint $table) {
                $table->decimal('sugar_percentage', 5, 2)->nullable();
            });
        }
    }
};
