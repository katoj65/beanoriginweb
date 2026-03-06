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
        if (! Schema::hasTable('commodity_price_index')) {
            Schema::create('commodity_price_index', function (Blueprint $table) {
                $table->id();
                $table->string('commodity');
                $table->string('type')->nullable();
                $table->string('grade')->nullable();
                $table->float('price');
                $table->float('min_price')->nullable();
                $table->float('max_price')->nullable();
                $table->decimal('quantity', 12, 2)->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('commodity_price_index', function (Blueprint $table) {
            if (! Schema::hasColumn('commodity_price_index', 'type')) {
                $table->string('type')->nullable()->after('commodity');
            }

            if (! Schema::hasColumn('commodity_price_index', 'grade')) {
                $table->string('grade')->nullable()->after('type');
            }

            if (! Schema::hasColumn('commodity_price_index', 'min_price')) {
                $table->float('min_price')->nullable()->after('price');
            }

            if (! Schema::hasColumn('commodity_price_index', 'max_price')) {
                $table->float('max_price')->nullable()->after('min_price');
            }

            if (! Schema::hasColumn('commodity_price_index', 'quantity')) {
                $table->decimal('quantity', 12, 2)->default(0)->after('max_price');
            }
        });

        if (Schema::hasColumn('commodity_price_index', 'metadata')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->dropColumn('metadata');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('commodity_price_index') && ! Schema::hasColumn('commodity_price_index', 'metadata')) {
            Schema::table('commodity_price_index', function (Blueprint $table) {
                $table->string('metadata')->nullable()->after('quantity');
            });
        }
    }
};
