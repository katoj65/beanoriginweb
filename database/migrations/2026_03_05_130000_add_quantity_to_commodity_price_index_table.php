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
        if (! Schema::hasColumn('commodity_price_index', 'quantity')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->decimal('quantity', 12, 2)->default(0)->after('price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('commodity_price_index', 'quantity')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
};
