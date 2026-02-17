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
        Schema::table('farmer_batch_verifications', function (Blueprint $table) {
            $table->unique('verification_code', 'fbv_verification_code_unique');
        });

        Schema::table('produces', function (Blueprint $table) {
            $table->unique('verification_code', 'produces_verification_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farmer_batch_verifications', function (Blueprint $table) {
            $table->dropUnique('fbv_verification_code_unique');
        });

        Schema::table('produces', function (Blueprint $table) {
            $table->dropUnique('produces_verification_code_unique');
        });
    }
};
