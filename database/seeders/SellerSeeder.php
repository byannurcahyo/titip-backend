<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sellers')->insert([
            'id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'user_id' => 'd61c05ed-e746-41fc-8450-0d2238975219',
            'store_name' => 'Maju Jaya Store',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('sellers')->insert([
            'id' => 'a73ced70-dd2a-4221-9bd5-dafdec918e4b',
            'user_id' => '5b655165-93d1-4a0f-87fa-39d546758f79',
            'store_name' => 'Sumber Makmur Store',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
