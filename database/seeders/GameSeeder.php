<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert([
            'id' => '1',
            'game_name' => 'Counter Strike',
            'user_id' => '2',
            'genre' => 'Shooter',
            'age' => '15',
            'developer' => 'Valve'
        ]);

        DB::table('games')->insert([
            'id' => '2',
            'game_name' => 'Dota 2',
            'user_id' => '3',
            'genre' => 'Strategy',
            'age' => '12',
            'developer' => 'Valve'
        ]);

        DB::table('games')->insert([
            'id' => '3',
            'game_name' => 'Call of Duty',
            'user_id' => '1',
            'genre' => 'Shooter',
            'age' => '15',
            'developer' => 'Activision'
        ]);
    }
}
