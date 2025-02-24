<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('address')->insert([
            'id' => 'c252c33b-48d6-4c6a-9028-81c9a2c77875',
            'user_id' => '8f3893c5-1458-453e-9473-b5e375d2441c',
            'address' => 'Jl. Raya Ciputat Parung No. 12',
            'city' => 'Tangerang Selatan',
            'province' => 'Banten',
            'postal_code' => '15412',
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('address')->insert([
            'id' => 'daaab1c6-59ba-47fa-8b76-8a4ff7927932',
            'user_id' => 'f1502db6-7357-42b6-b22c-bd4ca0cfac1f',
            'address' => 'Jl. Raya Ciputat Parung No. 13',
            'city' => 'Tangerang Selatan',
            'province' => 'Banten',
            'postal_code' => '15412',
            'phone' => '081234567891',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
