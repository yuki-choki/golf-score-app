<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $players = ['dummy', 'Aさん', 'Bさん', 'Cさん', 'Dさん', 'Eさん', 'Fさん'];
        $params = [];
        // 6行のレコードを作成
        for ($i = 1; $i < 7; $i++) {
            $params[$i]['player_name'] = $players[$i];
            $params[$i]['user_id'] = ($i === 1 || $i === 4) ? 1 : null;
            $params[$i]['game_id'] = $i < 4 ? 1 : 2;
            $params[$i]['update_job'] = 'gameSeeder';
            // 各数値を18ホール分作成
            for ($s = 1; $s < 19; $s++) {
                $params[$i]['score_' . $s] = rand(3, 7);
                $params[$i]['putter_' . $s] = rand(1, 3);
                // 4番、12番ホールはパー3
                if ($s === 4 || $s === 12) {
                    $params[$i]['par_' . $s] = 3;
                    $params[$i]['yard_' . $s] = 150;
                // 8番、15番ホールはパー5
                } elseif ($s === 8 || $s === 15) {
                    $params[$i]['par_' . $s] = 5;
                    $params[$i]['yard_' . $s] = 450;
                // それ以外はパー4
                } else {
                    $params[$i]['par_' . $s] = 4;
                    $params[$i]['yard_' . $s] = 300;
                }
            }
        }
        foreach ($params as $data) {
            DB::table('score_cards')->insert($data);
        }

    }
}
