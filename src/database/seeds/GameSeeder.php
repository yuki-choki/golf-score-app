<?php

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
        $params = [];
        for ($i = 0; $i < 10; $i++) {
            $params[$i]['user_id'] = 1;
            $params[$i]['corse_id'] = random_int(1, 10);
            $params[$i]['date'] = date('Y-m-d');
            $params[$i]['memo'] = 'テストメモ';
            $params[$i]['weather'] = '晴';
            $params[$i]['update_job'] = 'GameSeeder';
        }
        foreach ($params as $data) {
            DB::table('games')->insert($data);
        }
    }
}
