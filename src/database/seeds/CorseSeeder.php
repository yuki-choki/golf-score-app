<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CorseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全都道府県のゴルフコースデータ取得
        for ($prefCode = 1; $prefCode <= 47; $prefCode++) {
            $res = true;
            $page = 0;
            while ($res) {
                sleep(1);
                $page++;
                $client = new RakutenRws_Client();
                // アプリID (デベロッパーID) をセット
                $client->setApplicationId(env('RAKUTEN_APPLICATION_ID', false));
                // ゴルフ情報取得
                $response = $client->execute('GoraGoraGolfCourseSearch', [
                    'areaCode' => $prefCode, // 都道府県コード
                    'page' => $page // 取得するページを指定（1 ページあたり最大 30 件取得）
                ]);
                if ($response->isOk()) {
                    $corseDataArr = $response->getData()['Items'];
                    foreach ($corseDataArr as $corse) {
                        // データ保存処理
                        $data = [];
                        $data['name'] = $corse['Item']['golfCourseName'];
                        $data['address'] = $corse['Item']['address'];
                        $data['pref_code'] = $prefCode;
                        DB::table('corses')->insert([
                            'name' => $corse['Item']['golfCourseName'],
                            'address' => $corse['Item']['address'],
                            'pref_code' => $prefCode,
                            'update_job' => 'CorseSeeder',
                        ]);
                    }
                } else {
                    // too_many_requests エラーが発生した時は 3 秒待ってから再度処理を行う
                    if (strpos($response->getMessage(), 'too_many_requests') !== false) {
                        $page--;
                        sleep(3);
                        continue;
                    }
                    // 処理を抜ける
                    $res = false;
                }
            }
        }
        // 検証のために使用
        // $client = new RakutenRws_Client();
        // // アプリID (デベロッパーID) をセット
        // $client->setApplicationId(env('RAKUTEN_APPLICATION_ID', false));
        // // ゴルフ情報取得
        // $response = $client->execute('GoraGoraGolfCourseSearch', [
        //     'areaCode' => 1,
        //     // 'page' => 1
        // ]);
        // if ($response->isOk()) {
            // DB::table('corses')->insert([
            //     'name' => $response->getData()['Items'][0]['Item']['golfCourseName'],
            //     'address' => $response->getData()['Items'][0]['Item']['address'],
            //     'pref_code' => 1,
            //     'update_job' => 'CorseSeeder',
            // ]);
        // } else {
            // echo 'Error:'.$response->getMessage();
        // }
    }
}
