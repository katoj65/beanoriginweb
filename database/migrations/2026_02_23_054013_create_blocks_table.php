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
Schema::create('blocks', function (Blueprint $table) {
$table->id();
$table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
$table->unsignedBigInteger('block_index')->unique();
$table->string('current_hash');
$table->string('previous_hash');
$table->decimal('weight', 12, 2)->nullable();
$table->json('event_data')->nullable();
$table->string('event_type');
$table->foreignId('current_owner')->constrained('users');
$table->foreignId('previous_owner')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
Schema::dropIfExists('blocks');
}
};
