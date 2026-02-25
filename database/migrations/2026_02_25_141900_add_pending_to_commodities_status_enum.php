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
        DB::statement("ALTER TABLE commodities MODIFY COLUMN status ENUM('created','pending','listed','sold') NOT NULL DEFAULT 'created'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE commodities SET status = 'created' WHERE status = 'pending'");
        DB::statement("ALTER TABLE commodities MODIFY COLUMN status ENUM('created','listed','sold') NOT NULL DEFAULT 'created'");
    }
};
