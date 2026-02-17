<?php

namespace Database\Seeders;

use App\Models\CropType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CropTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cropTypes = [
            ['name' => 'arabica', 'description' => 'High-altitude coffee type with mild flavor profile.'],
            ['name' => 'robusta', 'description' => 'Hardy coffee type with stronger body and higher caffeine.'],
            ['name' => 'liberica', 'description' => 'Coffee type known for larger beans and distinct aroma.'],
            ['name' => 'excelsa', 'description' => 'Coffee type often blended for tart and fruity notes.'],
        ];

        foreach ($cropTypes as $cropType) {
            CropType::updateOrCreate(
                ['name' => $cropType['name']],
                ['description' => $cropType['description']]
            );
        }
    }
}
