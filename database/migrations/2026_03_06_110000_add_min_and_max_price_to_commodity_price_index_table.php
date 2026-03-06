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
        Schema::table('commodity_price_index', function (Blueprint $table) {
            if (! Schema::hasColumn('commodity_price_index', 'min_price')) {
                $table->float('min_price')->nullable()->after('price');
            }

            if (! Schema::hasColumn('commodity_price_index', 'max_price')) {
                $table->float('max_price')->nullable()->after('min_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commodity_price_index', function (Blueprint $table) {
            if (Schema::hasColumn('commodity_price_index', 'max_price')) {
                $table->dropColumn('max_price');
            }

            if (Schema::hasColumn('commodity_price_index', 'min_price')) {
                $table->dropColumn('min_price');
            }
        });
    }
};
