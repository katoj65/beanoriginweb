<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `batches` MODIFY `market_type` ENUM('save','marketplace','bidding') NOT NULL DEFAULT 'save'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `batches` SET `market_type` = 'marketplace' WHERE `market_type` = 'save'");
        DB::statement("ALTER TABLE `batches` MODIFY `market_type` ENUM('marketplace','bidding') NOT NULL DEFAULT 'marketplace'");
    }
};

