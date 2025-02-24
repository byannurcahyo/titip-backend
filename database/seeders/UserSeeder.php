<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 'd61c05ed-e746-41fc-8450-0d2238975219',
            'name' => 'Seller Landa 1',
            'email' => 'seller1@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'seller',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => '5b655165-93d1-4a0f-87fa-39d546758f79',
            'name' => 'Seller Landa 2',
            'email' => 'seller2@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'seller',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => '8f3893c5-1458-453e-9473-b5e375d2441c',
            'name' => 'User Landa 1',
            'email' => 'user1@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => 'f1502db6-7357-42b6-b22c-bd4ca0cfac1f',
            'name' => 'User Landa 2',
            'email' => 'user2@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => '52be6a5c-c647-4fdc-96db-0fa4e33d3c6c',
            'name' => 'Admin Landa',
            'email' => 'admin@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
