<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('batches', 'quantity')) {
            DB::statement('ALTER TABLE batches MODIFY quantity INT NOT NULL DEFAULT 0');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('batches', 'quantity')) {
            DB::statement('ALTER TABLE batches MODIFY quantity DECIMAL(12,2) NOT NULL DEFAULT 0');
        }
    }
};

