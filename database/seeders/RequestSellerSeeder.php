<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RequestSellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('request_sellers')->insert([
            'id' => '1c4b416d-a7ef-4f89-bcf6-d5cd5e2f36f5',
            'user_id' => 'd61c05ed-e746-41fc-8450-0d2238975219',
            'status' => 'approved',
            'reviewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('request_sellers')->insert([
            'id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'user_id' => '5b655165-93d1-4a0f-87fa-39d546758f79',
            'status' => 'approved',
            'reviewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('request_sellers')->insert([
            'id' => 'b72a4fad-c4db-418b-832f-eb970e688c1a',
            'user_id' => '8f3893c5-1458-453e-9473-b5e375d2441c',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
