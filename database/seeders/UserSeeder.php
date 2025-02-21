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
            'name' => 'Admin Landa',
            'email' => 'admin@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => '5b655165-93d1-4a0f-87fa-39d546758f79',
            'name' => 'User Landa',
            'email' => 'user@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
