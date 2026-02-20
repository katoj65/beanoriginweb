<?php

namespace Database\Seeders;

use App\Models\ChainBatchStatus;
use Illuminate\Database\Seeder;

class ChainBatchStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'created',
            'processing',
            'processed',
            'hulled',
            'graded',
            'listed',
            'sold',
        ];

        foreach ($statuses as $status) {
            ChainBatchStatus::updateOrCreate(
                ['name' => $status]
            );
        }
    }
}
