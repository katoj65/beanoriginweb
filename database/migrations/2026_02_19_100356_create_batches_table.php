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
            $table->foreignId('cooperative_id')->constrained('cooperative')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('batch_code')->unique();
            $table->string('crop_name');
            $table->string('crop_type');
            $table->decimal('quantity', 12, 2);
            $table->decimal('price', 12, 2);
            $table->string('location');
            $table->date('date_of_harvest');
            $table->string('crop_grade');
            $table->string('process_method');
            $table->string('status')->default('listed');
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
