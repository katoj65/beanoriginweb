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
        if (! Schema::hasColumn('commodity_price_index', 'metadata')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->string('metadata')->nullable()->after('quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('commodity_price_index', 'metadata')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->dropColumn('metadata');
            });
        }
    }
};
