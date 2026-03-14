<?php

namespace Database\Seeders;

use App\Models\BatchTradeActivityMetadata;
use Illuminate\Database\Seeder;

class BatchTradeActivityMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            'created',
            'processed',
            'listed',
            'inspected',
            'verified',
            'sold',
            'warehoused',
            'ownership transferred',
            'tokenized',
            'redeemed',
            'transported',
            'cleared at port'
        ];

        foreach ($activities as $activity) {
            BatchTradeActivityMetadata::query()->firstOrCreate([
                'activity' => $activity,
            ]);
        }
    }
}
