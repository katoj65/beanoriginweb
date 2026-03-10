<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SustainabilityMetadata;

class SustainabilityMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
$activity=[
'soil conservation',
'water management',
'organic practices',
'shade tree cover',
];

foreach($activity as $item){
SustainabilityMetadata::create(['activity'=>$item]);
}


    }
}
