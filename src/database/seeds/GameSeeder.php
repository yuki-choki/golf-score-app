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
        $courseIds = $this->getCourseIds();
        $params = [];
        for ($i = 0; $i < count($courseIds); $i++) {
            $params[$i]['user_id'] = 1;
            $params[$i]['corse_id'] = $courseIds[$i];
            $params[$i]['date'] = date('Y-m-d');
            $params[$i]['memo'] = 'テストメモ';
            $params[$i]['weather'] = '晴';
            $params[$i]['update_job'] = 'GameSeeder';
        }
        foreach ($params as $data) {
            DB::table('games')->insert($data);
        }
    }

    /**
     * 引数で指定した件数だけゴルフコースのIDを返す
     * 
     * @param int $limit
     * @return Illuminate\Support\Collection
     */
    private function getCourseIds($limit = 5)
    {
        return DB::table('corses')
                    ->select('id')
                    ->limit($limit)
                    ->get()
                    ->pluck('id');
    }
}
