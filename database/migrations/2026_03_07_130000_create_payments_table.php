<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**https://file+.vscode-resource.vscode-cdn.net/Users/mac/.vscode/extensions/openai.chatgpt-0.4.79-darwin-x64/webview/#
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('shopping_cart_session', 255)->nullable();
            $table->string('transaction_reference', 255)->nullable();
            $table->string('currency')->nullable();
            $table->unsignedInteger('number_of_items')->default(0);
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
