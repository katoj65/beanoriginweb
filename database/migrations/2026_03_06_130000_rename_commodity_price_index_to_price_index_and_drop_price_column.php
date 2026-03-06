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
        if (Schema::hasTable('commodity_price_index') && ! Schema::hasTable('price_index')) {
            Schema::rename('commodity_price_index', 'price_index');
        }

        if (! Schema::hasTable('price_index')) {
            Schema::create('price_index', function (Blueprint $table) {
                $table->id();
                $table->string('commodity');
                $table->string('type')->nullable();
                $table->string('grade')->nullable();
                $table->float('min_price')->nullable();
                $table->float('max_price')->nullable();
                $table->decimal('quantity', 12, 2)->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('price_index', function (Blueprint $table) {
            if (Schema::hasColumn('price_index', 'price')) {
                $table->dropColumn('price');
            }

            if (Schema::hasColumn('price_index', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('price_index')) {
            Schema::table('price_index', function (Blueprint $table) {
                if (! Schema::hasColumn('price_index', 'price')) {
                    $table->float('price')->nullable()->after('quantity');
                }

                if (! Schema::hasColumn('price_index', 'metadata')) {
                    $table->string('metadata')->nullable()->after('price');
                }
            });
        }

        if (Schema::hasTable('price_index') && ! Schema::hasTable('commodity_price_index')) {
            Schema::rename('price_index', 'commodity_price_index');
        }
    }
};
