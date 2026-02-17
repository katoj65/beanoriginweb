<?php

namespace Database\Seeders;

use App\Models\ProcessMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'washed',
                'description' => 'Coffee cherries are pulped, fermented, washed, and dried.',
            ],
            [
                'name' => 'natural',
                'description' => 'Coffee cherries are dried whole before hulling.',
            ],
            [
                'name' => 'honey',
                'description' => 'Some mucilage is left on parchment during drying.',
            ],
            [
                'name' => 'anaerobic',
                'description' => 'Fermentation is done in low-oxygen sealed environments.',
            ],
            [
                'name' => 'wet-hulled',
                'description' => 'Parchment is removed at high moisture before final drying.',
            ],
        ];

        foreach ($methods as $method) {
            ProcessMethod::updateOrCreate(
                ['name' => $method['name']],
                ['description' => $method['description']]
            );
        }
    }
}
