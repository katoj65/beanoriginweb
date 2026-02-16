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
        Schema::create('help_centers', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('priority')->default('normal');
            $table->string('subject');
            $table->text('description');
            $table->string('preferred_channel')->default('email');
            $table->string('contact_name')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('open');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cooperative_id')->constrained('cooperative')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_centers');
    }
};
