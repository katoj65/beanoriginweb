<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('cooperative_farmers')) {
            return;
        }

        if (Schema::hasTable('farmers')) {
            DB::statement("
                INSERT INTO farmers (
                    id, cooperative_id, first_name, last_name, phone_number, email, gender,
                    date_of_birth, national_id, district, sub_county, village, primary_crop, status,
                    created_at, updated_at
                )
                SELECT
                    cf.id, cf.cooperative_id, cf.first_name, cf.last_name, cf.phone_number, cf.email, cf.gender,
                    cf.date_of_birth, cf.national_id, cf.district, cf.sub_county, cf.village, cf.primary_crop, cf.status,
                    cf.created_at, cf.updated_at
                FROM cooperative_farmers cf
                LEFT JOIN farmers f ON f.id = cf.id
                WHERE f.id IS NULL
            ");
        }

        if (Schema::hasTable('farms') && Schema::hasColumn('farms', 'cooperative_farmer_id')) {
            try {
                Schema::table('farms', function (Blueprint $table) {
                    $table->dropForeign(['cooperative_farmer_id']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('farms', function (Blueprint $table) {
                $table->foreign('cooperative_farmer_id')->references('id')->on('farmers')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('farmer_batch_verifications') && Schema::hasColumn('farmer_batch_verifications', 'cooperative_farmers_id')) {
            try {
                Schema::table('farmer_batch_verifications', function (Blueprint $table) {
                    $table->dropForeign(['cooperative_farmers_id']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('farmer_batch_verifications', function (Blueprint $table) {
                $table->foreign('cooperative_farmers_id')->references('id')->on('farmers')->cascadeOnDelete();
            });
        }

        Schema::dropIfExists('cooperative_farmers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('cooperative_farmers')) {
            Schema::create('cooperative_farmers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cooperative_id')->constrained('cooperative')->cascadeOnDelete();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone_number', 30);
                $table->string('email')->nullable();
                $table->string('gender', 20)->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('national_id', 50)->nullable();
                $table->string('district')->nullable();
                $table->string('sub_county')->nullable();
                $table->string('village')->nullable();
                $table->string('primary_crop')->nullable();
                $table->enum('status', ['pending', 'active', 'suspended', 'exited'])->default('pending');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('farms') && Schema::hasColumn('farms', 'cooperative_farmer_id')) {
            try {
                Schema::table('farms', function (Blueprint $table) {
                    $table->dropForeign(['cooperative_farmer_id']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('farms', function (Blueprint $table) {
                $table->foreign('cooperative_farmer_id')->references('id')->on('cooperative_farmers')->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('farmer_batch_verifications') && Schema::hasColumn('farmer_batch_verifications', 'cooperative_farmers_id')) {
            try {
                Schema::table('farmer_batch_verifications', function (Blueprint $table) {
                    $table->dropForeign(['cooperative_farmers_id']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('farmer_batch_verifications', function (Blueprint $table) {
                $table->foreign('cooperative_farmers_id')->references('id')->on('cooperative_farmers')->cascadeOnDelete();
            });
        }
    }
};
