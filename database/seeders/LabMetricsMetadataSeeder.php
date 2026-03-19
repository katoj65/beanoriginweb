<?php

namespace Database\Seeders;

use App\Models\LabMetricMetadata;
use Illuminate\Database\Seeder;

class LabMetricsMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $metrics = [
            'moisture',
            'grade',
            'density',
            'screen size',
            'defects',
            'foreign matter',
            'bean size',
            'bean color',
            'cup score',
            'acidity',
            'aroma',
            'flavour',
        ];

        foreach ($metrics as $metric) {
            LabMetricMetadata::query()->firstOrCreate([
                'name' => $metric,
            ]);
        }
    }
}
