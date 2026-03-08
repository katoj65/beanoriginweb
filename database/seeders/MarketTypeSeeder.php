<?php

namespace Database\Seeders;

use App\Models\MarketType;
use Illuminate\Database\Seeder;

class MarketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'marketplace',
            'bidding',
        ];

        foreach ($types as $type) {
            MarketType::updateOrCreate(
                ['name' => $type]
            );
        }
    }
}

