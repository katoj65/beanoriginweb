<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessingMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'raw cherry',
            'processing',
            'drying',
            'parchment stored',
            'export ready',
        ];

        foreach ($items as $item) {
            DB::table('processing_metadata')->updateOrInsert(
                ['name' => $item],
                ['name' => $item, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
