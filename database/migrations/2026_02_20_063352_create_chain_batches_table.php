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
Schema::create('chain_batches', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->string('batch_number')->unique();
$table->string('grade')->nullable();
$table->decimal('weight', 12, 2)->nullable();
$table->enum('status', ['created', 'processing', 'processed', 'hulled', 'graded', 'listed', 'sold'])->default('created');
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
Schema::dropIfExists('chain_batches');
}
};
