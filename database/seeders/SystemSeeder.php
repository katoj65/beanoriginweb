<?php

namespace Database\Seeders;

use App\Models\System;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $systems = [
            'farming',
            'harvesting',
            'batching',
            'tokenising',
        ];

        foreach ($systems as $system) {
            System::updateOrCreate(
                ['name' => $system]
            );
        }
    }
}
