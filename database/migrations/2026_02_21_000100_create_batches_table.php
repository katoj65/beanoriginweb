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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('batch_code')->unique()->index();
            $table->string('commodity_name');
            $table->string('commodity_type');
            $table->decimal('weight', 12, 2);
            $table->string('grade')->nullable();
            $table->decimal('moisture', 5, 2)->nullable();
            $table->string('warehouse')->nullable();
            $table->boolean('is_on_chain')->default(false);
            $table->string('status')->default('created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
