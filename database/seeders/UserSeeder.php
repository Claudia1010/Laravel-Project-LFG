<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'id' => '1',
            'name' => 'Claudia',
            'email' => 'claudia@gmail.com',
		    'email_verified_at' => '2022-07-28 11:08:59',
            'password' => '123456',
        ]);

        DB::table('users')->insert([
            'id' => '2',
            'name' => 'Antonio',
            'email' => 'antonio@gmail.com',
		    'email_verified_at' => '2022-07-29 11:18:59',
            'password' => '123456',
        ]);

        DB::table('users')->insert([
            'id' => '3',
            'name' => 'Daniel',
            'email' => 'daniel@gmail.com',
		    'email_verified_at' => '2022-07-28 11:00:59',
            'password' => '123456',
        ]);
    }
}
