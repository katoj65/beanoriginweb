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
        Schema::table('farms', function (Blueprint $table) {
            if (!Schema::hasColumn('farms', 'farmer_last_name')) {
                $table->string('farmer_last_name')->nullable()->after('area_acres');
            }

            if (!Schema::hasColumn('farms', 'farmer_telephone')) {
                $table->string('farmer_telephone')->nullable()->after('farmer_last_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('farms', 'farmer_last_name')) {
                $dropColumns[] = 'farmer_last_name';
            }

            if (Schema::hasColumn('farms', 'farmer_telephone')) {
                $dropColumns[] = 'farmer_telephone';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
