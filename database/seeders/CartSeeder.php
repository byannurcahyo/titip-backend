<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carts')->insert([
            'id' => '1e6b2775-630e-4817-85bf-683674ebd8b1',
            'user_id' => 'f1502db6-7357-42b6-b22c-bd4ca0cfac1f',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
