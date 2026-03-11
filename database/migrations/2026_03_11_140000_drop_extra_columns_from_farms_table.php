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
            $dropColumns = [];

            if (Schema::hasColumn('farms', 'primary_crop')) {
                $dropColumns[] = 'primary_crop';
            }

            if (Schema::hasColumn('farms', 'soil_type')) {
                $dropColumns[] = 'soil_type';
            }

            if (Schema::hasColumn('farms', 'water_source_type')) {
                $dropColumns[] = 'water_source_type';
            }

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            if (!Schema::hasColumn('farms', 'primary_crop')) {
                $table->string('primary_crop')->nullable();
            }

            if (!Schema::hasColumn('farms', 'soil_type')) {
                $table->string('soil_type')->nullable();
            }

            if (!Schema::hasColumn('farms', 'water_source_type')) {
                $table->string('water_source_type')->nullable();
            }

            if (!Schema::hasColumn('farms', 'farmer_last_name')) {
                $table->string('farmer_last_name')->nullable();
            }

            if (!Schema::hasColumn('farms', 'farmer_telephone')) {
                $table->string('farmer_telephone')->nullable();
            }
        });
    }
};
