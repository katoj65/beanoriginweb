<?php

namespace Database\Seeders;

use App\Models\BatchPurchaseAction;
use Illuminate\Database\Seeder;

class BatchPurchaseActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'intent',
            'accept',
            'reject',
            'reserved',
            'purchased',
            'cancelled',
            'completed',
        ];

        foreach ($actions as $action) {
            BatchPurchaseAction::updateOrCreate(
                ['name' => $action]
            );
        }
    }
}
