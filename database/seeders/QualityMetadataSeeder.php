<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QualityMetadata;

class QualityMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

$data=[
'moisture',
'bean size',
'bean density',
'bean color',
'broken bean percentage',
'foreign matter presence',
'process method',
'drying method',
'fermentation time',
'process date',
'grade',
'weight',
'variety',
'cup score',
'defects',
'flavour',
'aroma',
'acidity',
];

foreach($data as $item){
QualityMetadata::create(['activity'=>$item]);
}




    }
}
