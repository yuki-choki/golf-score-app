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
        $courseName = ['OUT', 'IN'];
        $users = array_column(DB::table('users')->get()->toArray(), null, 'id');
        // 5ラウンド分のレコードを作成（IN、OUTそれぞれ5ラウンド分）
        for ($i = 1; $i < 11; $i++) {
            $startFlag = 0;
            if ($i % 2 === 0) {
                $startFlag = 1;
            }
            // 1ラウンドにつき、3人分のスコアを登録
            for ($user_id = 1; $user_id < 4; $user_id++) {
                $key = $i . '-' . $user_id;
                $params[$key]['player_name'] = $users[$user_id]->name;
                $params[$key]['user_id'] = $user_id;
                $params[$key]['game_id'] = ceil($i / 2);
                $params[$key]['start_flag'] = $startFlag;
                $params[$key]['course_name'] = $courseName[$startFlag];
                $params[$key]['update_job'] = 'gameSeeder';
                // 各数値を9ホール分作成
                for ($s = 1; $s < 10; $s++) {
                    $params[$key]['score_' . $s] = rand(3, 7);
                    $params[$key]['putter_' . $s] = rand(1, 3);
                    // 2番、8番ホールはパー3
                    if ($s === 2 || $s === 8) {
                        $params[$key]['par_' . $s] = 3;
                        $params[$key]['yard_' . $s] = 150;
                    // 3番、9番ホールはパー5
                    } elseif ($s === 3 || $s === 9) {
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
