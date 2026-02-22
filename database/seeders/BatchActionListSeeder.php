<?php

namespace Database\Seeders;

use App\Models\BatchActionList;
use Illuminate\Database\Seeder;

class BatchActionListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actions = [
            'created',
            'roasted',
            'bought',
            'shipped',
            'delivered',
            'exported',
            'stored',
            'inspected',
            'packed',
            'listed',
        ];

        foreach ($actions as $action) {
            BatchActionList::updateOrCreate(
                ['name' => $action]
            );
        }
    }
}

