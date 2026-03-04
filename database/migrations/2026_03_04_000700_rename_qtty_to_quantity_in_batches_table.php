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
        if (Schema::hasColumn('batches', 'qtty') && !Schema::hasColumn('batches', 'quantity')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->renameColumn('qtty', 'quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('batches', 'quantity') && !Schema::hasColumn('batches', 'qtty')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->renameColumn('quantity', 'qtty');
            });
        }
    }
};

