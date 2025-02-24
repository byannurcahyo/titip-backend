<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cart_items')->insert([
            'id' => '0b1340cf-d609-43a5-a6b2-f8c3fb523bc4',
            'cart_id' => '1e6b2775-630e-4817-85bf-683674ebd8b1',
            'product_id' => 'b84af817-97d2-4329-ac25-af7a08dd2129',
            'quantity' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('cart_items')->insert([
            'id' => '68b79a9c-3421-46fb-afdf-0298cb271c33',
            'cart_id' => '1e6b2775-630e-4817-85bf-683674ebd8b1',
            'product_id' => 'f4edcc14-06b1-490c-a81f-4817dfc7c5b5',
            'quantity' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
