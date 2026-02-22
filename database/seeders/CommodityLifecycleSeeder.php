<?php

namespace Database\Seeders;

use App\Models\CommodityLifecycle;
use Illuminate\Database\Seeder;

class CommodityLifecycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lifecycles = [
            'created',
            'processed',
            'dried and stored',
            'dried and milled',
            'export ready',
        ];

        foreach ($lifecycles as $name) {


            CommodityLifecycle::updateOrCreate(
                ['name' => $name],
                ['description' => $name],
                ['status' => $name]
            );
        }
    }
}
