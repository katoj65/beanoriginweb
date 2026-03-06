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
            if (! Schema::hasColumn('commodity_price_index', 'type')) {
                $table->string('type')->nullable()->after('commodity');
            }

            if (! Schema::hasColumn('commodity_price_index', 'grade')) {
                $table->string('grade')->nullable()->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commodity_price_index', function (Blueprint $table) {
            if (Schema::hasColumn('commodity_price_index', 'grade')) {
                $table->dropColumn('grade');
            }

            if (Schema::hasColumn('commodity_price_index', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
