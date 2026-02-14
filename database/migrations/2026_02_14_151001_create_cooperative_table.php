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
        Schema::create('cooperative', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('name');
            $table->string('reg_num');
            $table->date('reg_date');
            $table->string('district');
            $table->string('physical_address');
            $table->string('postal_address');
            $table->string('email');
            $table->string('tel');
            $table->string('website');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative');
    }
};
