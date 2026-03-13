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
        DB::statement("ALTER TABLE `batches` MODIFY `is_on_chain` ENUM('false','true') NULL DEFAULT 'false'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `batches` MODIFY `is_on_chain` ENUM('false','true') NOT NULL DEFAULT 'false'");
    }
};

