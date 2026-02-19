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
Schema::create('lots', function (Blueprint $table) {
$table->id();
$table->foreignId('cooperative_id')->constrained('cooperative')->cascadeOnDelete();
$table->string('lot_number')->unique();
$table->decimal('weight', 12, 2);
$table->string('quality_summary');
$table->decimal('price', 15, 2);
$table->enum('status', ['draft', 'listed', 'sold', 'dispatched'])->default('draft');
$table->timestamps();
});
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
Schema::dropIfExists('lots');
}
};
