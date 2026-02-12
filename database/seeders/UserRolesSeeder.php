<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRoles;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

$data=['admin','cooperative','farmer','exporter','buyer','financier','business'];
foreach($data as $item){
UserRoles::create(['role'=>$item]);
}





    }
}
