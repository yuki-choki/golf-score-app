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
        $params = [];
        // 10ラウンド分のレコードを作成
        for ($i = 1; $i < 11; $i++) {
            // 1ラウンドにつき、3人分のスコアを登録
            for ($g_s = 0; $g_s < 3; $g_s++) {
                $key = $i . '-' . $g_s;
                $params[$key]['player_name'] = $key . 'さん';
                $params[$key]['user_id'] = ($g_s === 0) ? 1 : null;
                $params[$key]['game_id'] = $i;
                $params[$key]['update_job'] = 'gameSeeder';
                // 各数値を18ホール分作成
                for ($s = 1; $s < 19; $s++) {
                    $params[$key]['score_' . $s] = rand(3, 7);
                    $params[$key]['putter_' . $s] = rand(1, 3);
                    // 4番、12番ホールはパー3
                    if ($s === 4 || $s === 12) {
                        $params[$key]['par_' . $s] = 3;
                        $params[$key]['yard_' . $s] = 150;
                    // 8番、15番ホールはパー5
                    } elseif ($s === 8 || $s === 15) {
                        $params[$key]['par_' . $s] = 5;
                        $params[$key]['yard_' . $s] = 450;
                    // それ以外はパー4
                    } else {
                        $params[$key]['par_' . $s] = 4;
                        $params[$key]['yard_' . $s] = 300;
                    }
                }
            }
        }
        foreach ($params as $data) {
            DB::table('score_cards')->insert($data);
        }

    }
}
