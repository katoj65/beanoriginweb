<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        User::factory()->withPersonalTeam()->create([
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'test@example.com',
        ]);



        $this->call([
            UserRolesSeeder::class,
            CropTypeSeeder::class,
            ProcessMethodSeeder::class,
            CropGradeSeeder::class,
            ChainBatchStatusSeeder::class,
            BatchStatusListSeeder::class,
            BatchActionListSeeder::class,
            BatchPurchaseActionSeeder::class,
            MarketTypeSeeder::class,
            CommodityLifecycleSeeder::class,
            SystemSeeder::class,
            BatchTradeActivityMetadataSeeder::class,
            SustainabilityMetadataSeeder::class,
            ProcessingMetadataSeeder::class,
            LabMetricsMetadataSeeder::class,
        ]);



    }
}
