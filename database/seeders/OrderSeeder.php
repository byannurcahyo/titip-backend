<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            'id' => 'bdd216ec-78de-4fc3-b0f0-736edb8e30c3',
            'user_id' => 'f1502db6-7357-42b6-b22c-bd4ca0cfac1f',
            'address_id' => 'c252c33b-48d6-4c6a-9028-81c9a2c77875',
            'address' => 'Jl. Raya Ciputat Parung No. 12',
            'total_price' => 990000,
            'status' => 'waiting_payment',
            'invoice_number' => 'INV202502210001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
