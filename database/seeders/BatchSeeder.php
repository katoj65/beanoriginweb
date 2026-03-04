<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BatchSeeder extends Seeder
{
    /**
     * Seed 100 batch records distributed across different users.
     */
    public function run(): void
    {
        $ownerIds = User::query()->pluck('id')->values();

        // Ensure we have multiple owners so batches are distributed by user_id.
        if ($ownerIds->count() < 4) {
            $toCreate = 4 - $ownerIds->count();
            for ($i = 0; $i < $toCreate; $i++) {
                $user = User::query()->create([
                    'fname' => 'Seeder',
                    'lname' => 'User'.($i + 1),
                    'email' => 'seeder.user'.Str::lower(Str::random(6)).'@example.com',
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]);
                $ownerIds->push($user->id);
            }
        }

        $commodityNames = ['coffee', 'arabica', 'robusta', 'green coffee'];
        $commodityTypes = ['washed', 'natural', 'honey', 'semi-washed'];
        $grades = ['A', 'B', 'C', 'AA', 'AB'];
        $warehouses = ['Kampala', 'Mbarara', 'Gulu', 'Mbale', 'Jinja'];
        $statuses = ['created', 'verified', 'listed', 'sold', 'tokenised'];
        $isOnChainValue = $this->resolveIsOnChainSeedValue();

        for ($i = 1; $i <= 100; $i++) {
            $weight = mt_rand(500, 5000) / 10; // 50.0 - 500.0
            $quantity = mt_rand(10, 3000) / 10; // 1.0 - 300.0

            DB::table('batches')->insert([
                'owner_id' => $ownerIds[($i - 1) % $ownerIds->count()],
                'batch_code' => 'BATCH-'.now()->format('YmdHis').'-'.$i.'-'.Str::upper(Str::random(4)),
                'commodity_name' => $commodityNames[array_rand($commodityNames)],
                'commodity_type' => $commodityTypes[array_rand($commodityTypes)],
                'weight' => $weight,
                'quantity' => $quantity,
                'grade' => $grades[array_rand($grades)],
                'moisture' => mt_rand(90, 130) / 10, // 9.0 - 13.0
                'warehouse' => $warehouses[array_rand($warehouses)],
                'is_on_chain' => $isOnChainValue,
                'price' => mt_rand(50000, 250000),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(mt_rand(0, 60)),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Resolve a compatible seed value for batches.is_on_chain across enum/boolean schemas.
     */
    private function resolveIsOnChainSeedValue(): string|int
    {
        $column = DB::selectOne("SHOW COLUMNS FROM batches LIKE 'is_on_chain'");
        $type = strtolower((string) ($column->Type ?? ''));

        if (str_contains($type, "enum('true','false')")) {
            return 'false';
        }

        if (str_contains($type, "enum('0','1')")) {
            return '0';
        }

        return 0;
    }
}
