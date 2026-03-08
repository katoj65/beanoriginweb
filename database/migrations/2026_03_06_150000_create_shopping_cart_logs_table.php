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
Schema::create('shopping_cart_logs', function (Blueprint $table) {
$table->id();
$table->foreignId('shopping_cart_id')->nullable()->constrained('shopping_carts')->nullOnDelete();
$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
$table->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
$table->string('shopping_session', 255)->nullable();
$table->decimal('quantity', 12, 2)->default(0);
$table->decimal('unit_price', 12, 2);
$table->decimal('total_price', 14, 2);
$table->string('currency', 10)->default('UGX');
$table->string('payment_method', 100)->nullable();
$table->string('transaction_reference', 255)->nullable();
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
        Schema::dropIfExists('shopping_cart_logs');
    }
};
