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
Schema::create('block_purchases', function (Blueprint $table) {
$table->id();
$table->foreignId('block_id')->constrained('blocks')->onDelete('cascade');
$table->decimal('purchase_price', 10, 2);
$table->string('currency', 10)->nullable();
$table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
$table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
$table->string('transaction_reference', 255)->nullable();
$table->string('payment_method', 255)->nullable();
$table->string('status', 50)->default('pending');
$table->text('notes')->nullable();
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
Schema::dropIfExists('block_purchases');
}
};
