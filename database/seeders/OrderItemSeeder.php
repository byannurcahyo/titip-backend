<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_items')->insert([
            'id' => 'ddf2dee6-d374-4f30-9a98-4df8edd83ba0',
            'order_id' => 'bdd216ec-78de-4fc3-b0f0-736edb8e30c3',
            'seller_id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'product_id' => 'b84af817-97d2-4329-ac25-af7a08dd2129',
            'product_name' => 'Jersey Liverpool Home',
            'price' => 495000,
            'description' => 'Jersey Liverpool Home 2021/2022',
            'quantity' => 1,
            'subTotal' => 495000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('order_items')->insert([
            'id' => 'bae62ddf-db15-4b49-b21c-9ad014663f9b',
            'order_id' => 'bdd216ec-78de-4fc3-b0f0-736edb8e30c3',
            'seller_id' => '98181e16-ba38-491e-b90b-e5eaa270e69a',
            'product_id' => 'f4edcc14-06b1-490c-a81f-4817dfc7c5b5',
            'product_name' => 'Jersey Liverpool Away',
            'price' => 495000,
            'description' => 'Jersey Liverpool Away 2021/2022',
            'quantity' => 1,
            'subTotal' => 495000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
