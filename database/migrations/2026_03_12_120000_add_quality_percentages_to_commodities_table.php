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
        Schema::table('commodities', function (Blueprint $table) {
            $table->decimal('ripe_percentage', 5, 2)->nullable()->after('price');
            $table->decimal('density_percentage', 5, 2)->nullable()->after('ripe_percentage');
            $table->decimal('sugar_percentage', 5, 2)->nullable()->after('density_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commodities', function (Blueprint $table) {
            $table->dropColumn([
                'ripe_percentage',
                'density_percentage',
                'sugar_percentage',
            ]);
        });
    }
};
