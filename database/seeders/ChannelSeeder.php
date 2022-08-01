<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->insert([
            'id' => '1',
            'channel_name' => 'CS:GO',
            'game_id' => '1'
        ]);

        DB::table('channels')->insert([
            'id' => '2',
            'channel_name' => 'COD fans',
            'game_id' => '3'
        ]);

        DB::table('channels')->insert([
            'id' => '3',
            'channel_name' => 'Dota fever',
            'game_id' => '2'
        ]);
    
    }
}
