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
        Schema::create('commodity_batches', function (Blueprint $table) {
            $table->foreignId('commodity_id')->constrained('commodities')->cascadeOnDelete();
            $table->foreignId('chain_batch_id')->constrained('chain_batches')->cascadeOnDelete();
            $table->string('status')->default('created');
            $table->timestamps();
            $table->unique(['commodity_id', 'chain_batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_batches');
    }
};
