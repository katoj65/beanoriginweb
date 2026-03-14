<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->unsignedBigInteger('token_index')->unique();
            $table->string('event_type');
            $table->json('metadata')->nullable();
            $table->string('current_hash')->unique();
            $table->string('previous_hash')->nullable();
            $table->foreignId('current_owner')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('previous_owner')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'redeemed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token');
    }
};
