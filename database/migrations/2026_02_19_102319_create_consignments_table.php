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
Schema::create('consignments', function (Blueprint $table) {
$table->id();
$table->string('consignment_ref')->unique(); // e.g., CN-BEAN-001
$table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
$table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
$table->string('transporter_name')->nullable();
$table->string('vehicle_reg')->nullable();
$table->string('container_no')->nullable();
$table->decimal('total_net_weight', 15, 2);
$table->decimal('total_gross_weight', 15, 2);
$table->timestamp('dispatched_at')->nullable();
$table->date('estimated_delivery')->nullable();
$table->enum('status', ['processing', 'shipped', 'delivered', 'held_at_customs'])->default('processing');
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
Schema::dropIfExists('consignments');
}
};
