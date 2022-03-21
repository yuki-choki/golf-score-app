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
        $courseIds = DB::table('corses')->select('id')->get()->pluck('id')->toArray();
        // 全都道府県のゴルフコースデータ取得
        for ($prefCode = 1; $prefCode <= 47; $prefCode++) {
            $res = true;
            $page = 0;
            while ($res) {
                sleep(1);
                $page++;
                $client = new RakutenRws_Client();
                // アプリID (デベロッパーID) をセット
                $client->setApplicationId(config('app.rakuten_application_id', false));
                // ゴルフ情報取得
                $response = $client->execute('GoraGoraGolfCourseSearch', [
                    'areaCode' => $prefCode, // 都道府県コード
                    'page' => $page // 取得するページを指定（1 ページあたり最大 30 件取得）
                ]);
                if ($response->isOk()) {
                    $corseDataArr = $response->getData()['Items'];
                    foreach ($corseDataArr as $corse) {
                        if (in_array($corse['Item']['golfCourseId'], $courseIds)) {
                            continue;
                        }
                        usleep(300000); // 0.3秒間隔で処理を実行
                        $detail = $client->execute('GoraGoraGolfCourseDetail', [
                            'golfCourseId' => $corse['Item']['golfCourseId'],
                        ]);
                        // 「too_many_requests ~」のエラーが出た場合は 3秒待って再度実行
                        if (strpos($detail->getMessage(), 'too_many_requests') !== false) {
                            sleep(3);
                            $detail = $client->execute('GoraGoraGolfCourseDetail', [
                                'golfCourseId' => $corse['Item']['golfCourseId'],
                            ]);
                        }
                        $courseName = explode('・', $detail->getData()['Item']['courseName']);
                        $data = [];
                        $data['id'] = $corse['Item']['golfCourseId'];
                        $data['name'] = $corse['Item']['golfCourseName'];
                        $data['address'] = $corse['Item']['address'];
                        $data['course_name'] = json_encode($courseName, JSON_UNESCAPED_UNICODE);
                        $data['pref_code'] = $prefCode;
                        $data['update_job'] = 'CorseSeeder';
                        DB::table('corses')->insert($data);
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
    }
}
