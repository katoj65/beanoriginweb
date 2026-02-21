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
        Schema::create('batch_chain_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('chain_block_id')->nullable()->constrained('chain_blocks')->nullOnDelete();
            $table->string('event_type')->index();
            $table->json('event_data')->nullable();
            $table->string('status')->default('recorded')->index();
            $table->string('tx_hash')->nullable()->index();
            $table->timestamp('occurred_at')->nullable()->index();
            $table->timestamps();
            $table->index(['batch_id', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_chain_events');
    }
};

