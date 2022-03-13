<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class updateCourseDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateCourseDataCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '楽天GORAでのゴルフコース取得API実行。ゴルフコースデータを最新状態にする';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 既存のゴルフコース情報を削除
        DB::table('corses')->truncate();
        DB::unprepared("ALTER TABLE corses AUTO_INCREMENT = 1");

        // 全都道府県のゴルフコースデータ取得
        for ($prefCode = 1; $prefCode <= 47; $prefCode++) {
            $res = true;
            $page = 0;
            while ($res) {
                sleep(1);
                $page++;
                $client = new \RakutenRws_Client();
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
                        $data['name'] = $corse['Item']['golfCourseName'];
                        $data['address'] = $corse['Item']['address'];
                        $data['course_name'] = json_encode($courseName, JSON_UNESCAPED_UNICODE);
                        $data['pref_code'] = $prefCode;
                        $data['update_job'] = 'updateCourseDataCommand';
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
