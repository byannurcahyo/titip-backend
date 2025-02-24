<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'id' => 'b84af817-97d2-4329-ac25-af7a08dd2129',
            'seller_id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'name' => 'Jersey Liverpool Home',
            'description' => 'Jersey Liverpool Home 2021/2022',
            'price' => 495000,
            'stock' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'id' => 'f4edcc14-06b1-490c-a81f-4817dfc7c5b5',
            'seller_id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'name' => 'Jersey Liverpool Away',
            'description' => 'Jersey Liverpool Away 2021/2022',
            'price' => 495000,
            'stock' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'id' => '5e04d90a-7e04-40d9-9c1a-aac6db7f2a99',
            'seller_id' => 'a73ced70-dd2a-4221-9bd5-dafdec918e4b',
            'name' => 'Jersey Real Madrid Home',
            'description' => 'Jersey Real Madrid Home 2021/2022',
            'price' => 495000,
            'stock' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('products')->insert([
            'id' => '153f8a7d-68f7-4b6c-92ab-0c20e6e11746',
            'seller_id' => 'a73ced70-dd2a-4221-9bd5-dafdec918e4b',
            'name' => 'Jersey Real Madrid Away',
            'description' => 'Jersey Real Madrid Away 2021/2022',
            'price' => 495000,
            'stock' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
