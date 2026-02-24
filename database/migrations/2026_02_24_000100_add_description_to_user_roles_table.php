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
        Schema::table('user_roles', function (Blueprint $table) {
            $table->string('description')->nullable()->after('role');
             $table->string('icon')->nullable()->after('description');
              $table->string('url')->nullable()->after('icon');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropColumn('description');
                $table->dropColumn('icon');
                $table->dropColumn('url');
        });
    }
};
