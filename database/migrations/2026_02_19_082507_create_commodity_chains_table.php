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
        Schema::create('commodity_chains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produces_id')->constrained('produces')->cascadeOnDelete();
            $table->foreignId('cooperative_id')->constrained('cooperative')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('commodity_name');
            $table->string('commodity_type');
            $table->string('grade');
            $table->decimal('weight', 10, 2);
            $table->date('harvest_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_chains');
    }
};
