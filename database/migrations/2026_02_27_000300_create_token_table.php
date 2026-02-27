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
        Schema::create('token', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks')->cascadeOnDelete();
            $table->unsignedBigInteger('token_index')->unique();
            $table->string('token_hash')->unique();
            $table->string('previous_hash')->nullable();
            $table->decimal('weight', 12, 2)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->json('event_data')->nullable();
            $table->string('event_type')->nullable();
            $table->enum('status', ['active', 'redeemed'])->default('active');
            $table->foreignId('current_owner')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('previous_owner')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token');
    }
};
