<?php

namespace Database\Seeders;

use App\Models\CropGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CropGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['name' => 'AA', 'description' => 'Top screen size and premium quality beans.'],
            ['name' => 'A', 'description' => 'High quality beans with strong market demand.'],
            ['name' => 'AB', 'description' => 'Mixed medium-to-large bean grade.'],
            ['name' => 'B', 'description' => 'Standard commercial grade beans.'],
            ['name' => 'PB', 'description' => 'Peaberry beans with rounded shape and concentrated flavor.'],
            ['name' => 'C', 'description' => 'Lower grade beans typically used in blends.'],
        ];

        foreach ($grades as $grade) {
            CropGrade::updateOrCreate(
                ['name' => $grade['name']],
                ['description' => $grade['description']]
            );
        }
    }
}
