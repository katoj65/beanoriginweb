<?php

namespace Database\Seeders;

use App\Models\BatchStatusList;
use Illuminate\Database\Seeder;

class BatchStatusListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'created',
            'verified',
            'listed',
            'negotiation',
            'sold',
            'financed',
            'tokenised',
            'exported',
            'settled',
            'closed',
        ];

        foreach ($statuses as $status) {
            BatchStatusList::updateOrCreate(
                ['name' => $status]
            );
        }
    }
}
