<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\User;
use App\Corse;
use App\ScoreCard;
use Illuminate\Support\Facades\Auth;
use Storage;
use Log;

class ScoreController extends Controller
{
    const MAX_FILE_SIZE = 2; // MB

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::all();
        $games->load('score_cards');
        foreach ($games as $key => $game) {
            //対象ゲームのユーザーのスコアを取得する
            $score_cards = $game->score_cards->where('user_id', Auth::id());
            $game->total_score = 0;
            $game->total_putter = 0;
            foreach ($score_cards as $score_card) {
                $game->total_score += $score_card->getScoreCount('score');
                $game->total_putter += $score_card->getScoreCount('putter');
            }
        }

        return view('score.index', compact('games'));
    }

    /**
     * Search a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchData = [];
        $params = $request->input();
        if ($params) {
            $searchData = Corse::SearchCorse($params)->paginate(15);
        }

        return view('score.search', compact('searchData', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $corse = [];
        $maxSize = self::MAX_FILE_SIZE * (1024 * 1024);
        if ($request->method() === 'POST') {
            $params = $request->all();
            unset($params['_token']);
            $corse = Corse::find($params['pref_id']);
        } else {
            return redirect()->route('scores.search');
        }

        return view('score.create', compact('corse', 'params', 'request', 'maxSize'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $base64_image = $request->input('image');
            $now = date('Y_m_d_H_i_s');
            $fileName = Auth::id() . '_' . $now . '.png';

            // S3に保存
            Storage::disk('put_s3')->put($fileName, $base64_image);

            // デバッグ用
            // Storage::disk('local')->put('public/' . $fileName, $base64_image);

            // エラーハンドリング処理記述
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::find($id);
        $names = $this->getPlayerName($game);
        $round = $this->createScoreTable($game);
        $total = $this->getTotalScore($game);

        return view('score.show', compact('game', 'round', 'names', 'total'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = Game::find($id);
        $names = $this->getPlayerName($game);
        $round = $this->createScoreTable($game);
        $total = $this->getTotalScore($game);

        return view('score.edit', compact('game', 'names', 'round', 'total'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $game = Game::find($id);
        $params = $request->all();
        unset($params['_token'], $params['_method']);
        $score_cards = $game->score_cards;
        foreach ($score_cards as $score) {
            $score->fill($params[$score->id]);
            $score->save();
        }
        $game->memo = $params['memo'];
        $game->save();
        session()->flash('msg_success', 'スコアを編集しました');

        return redirect()->route('scores.show', ['score' => $game->id]);
    }

    public function saveData(Request $request)
    {
        $params = $request->all();
        var_dump($params);
        exit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function analysis(Request $request)
    {
        $my_games = Game::all();
        $my_games->load('corse')->toArray();
        $corse_lists = ['0' => '未選択'];
        $params = [];
        $games = [];
        $putters = [
            'zero_put' => 0,
            'one_put' => 0,
            'two_put' => 0,
            'other' => 0
        ];
        $scores = [
            'eagle' => 0,
            'birdie' => 0,
            'par' => 0,
            'bogey' => 0,
            'd_bogey' => 0,
            'other' => 0
        ];
        foreach ($my_games as $game) {
            $corse = $game['corse']->toArray();
            $corse_lists[$corse['id']] = $corse['name'];
        }
        if ($request->getMethod() === 'POST') {
            $params = $request->all();
            $games = Game::AnalysisSearch($params)->get();
            $games->load('score_cards');
            foreach ($games as $key => $game) {
                //対象ゲームのユーザーのスコアを取得する
                $score_cards = $game->score_cards->where('user_id', Auth::id());
                $game->total_score = 0;
                $game->total_putter = 0;
                foreach ($score_cards as $score_card) {
                    $game->total_score += $score_card->getScoreCount('score');
                    $game->total_putter += $score_card->getScoreCount('putter');
                    $put_scores[] = $score_card->putterCount();
                    $score_type_tally[] = $score_card->scoreTypeTally();
                }
            }
            foreach ($put_scores ?? [] as $put) {
                $putters['zero_put'] += $put['zero_put'];
                $putters['one_put'] += $put['one_put'];
                $putters['two_put'] += $put['two_put'];
                $putters['other'] += $put['other'];
            }
            foreach ($score_type_tally ?? [] as $score) {
                $scores['eagle'] += $score['eagle'];
                $scores['birdie'] += $score['birdie'];
                $scores['par'] += $score['par'];
                $scores['bogey'] += $score['bogey'];
                $scores['d_bogey'] += $score['d_bogey'];
                $scores['other'] += $score['other'];
            }
        }
        return view('score.analysis', compact('corse_lists', 'params', 'games', 'putters', 'scores'));
    }

    private function createScoreTable($game)
    {
        $res = [];
        // View にテーブル形式で表示するためにデータを形成
        foreach ($game->score_cards as $score) {
            $halfType = $score->start_flag ? 'second' : 'first';
            for ($i = 1; $i < 10; $i++) {
                if ($score->user_id == Auth::id()) {
                    $res[$halfType]['yard'][$i] = $score->{'yard_' . $i};
                    $res[$halfType]['par'][$i] = $score->{'par_' . $i};
                    $res[$halfType]['owner']['score'][$i] = $score->{'score_' . $i};
                    $res[$halfType]['owner']['putter'][$i] = $score->{'putter_' . $i};
                } else {
                    $res[$halfType]['companion'][$score->id][$i] = $score->{'score_' . $i};
                }
            }
            if ($score->user_id == Auth::id()) { // 本人のスコアとコースデータの設定
                $res[$halfType]['course'] = $score->course_name;
                $res[$halfType]['total']['yard'] = $score->getScoreCount('yard');
                $res[$halfType]['total']['par'] = $score->getScoreCount('par');
                $res[$halfType]['owner']['total']['score'] = $score->getScoreCount('score');
                $res[$halfType]['owner']['total']['putter'] = $score->getScoreCount('putter');
                $res[$halfType]['owner']['player_name'] = $score->player_name;
                $res[$halfType]['owner']['score_id'] = $score->id;
            } else { // 同伴者のスコアを設定
                $res[$halfType]['companion'][$score->id][0] = $score->player_name;
                $res[$halfType]['companion'][$score->id][10] = $score->getScoreCount('score');
                $res[$halfType]['companion'][$score->id][11] = $score->user_id;
                ksort($res[$halfType]['companion'][$score->id]);
            }
        }
        return $res;
    }

    private function getTotalScore($game)
    {
        $total = [];
        $owner = [];
        $setting = [];
        foreach ($game->score_cards as $score) {
            if ($score->user_id != Auth::id()) {
                if (isset($total[$score->user_id])) {
                    $total[$score->user_id] += $score->getScoreCount('score');
                } else {
                    $total[$score->user_id] = $score->getScoreCount('score');
                }
            } else {
                if (isset($owner['score'])) {
                    $owner['score'] += $score->getScoreCount('score');
                } else {
                    $owner['score'] = $score->getScoreCount('score');
                }
                if (isset($owner['putter'])) {
                    $owner['putter'] += $score->getScoreCount('putter');
                } else {
                    $owner['putter'] = $score->getScoreCount('putter');
                }
                if (isset($setting['par'])) {
                    $setting['par'] += $score->getScoreCount('par');
                    $setting['yard'] += $score->getScoreCount('yard');
                } else {
                    $setting['par'] = $score->getScoreCount('par');
                    $setting['yard'] = $score->getScoreCount('yard');
                }
            }
        }
        $total['owner'] = $owner;
        $total['setting'] = $setting;
        return $total;
    }

    private function getPlayerName($game)
    {
        $names = [];
        foreach ($game->score_cards as $score) {
            if ($score->start_flag == 0) {
                $names[$score->id] = $score->player_name;
            }
        }
        return $names;
    }

    /**
     * s3からスコアカードのtextファイルを取得する
     *
     */
    public function getS3Text()
    {
        // fly-system-s3aパッケージを使う
        // 設定ファイルはconfig/filesystems.phpにある
        $disk = Storage::disk('get_s3');
        $score_card_text_path = 'golftest5.txt';
        // $score_card_png_path =

        if ($disk->exists($score_card_text_path)) {
            // ファイル取得はget()
            $s3_contents_raw = $disk->get($score_card_text_path);
            $s3_contents_array = json_decode($s3_contents_raw);
            // var_dump($s3_contents_array);

            // -------------------------テーブル表示のための加工-------------------------
            // 表の三行目(ヘッダー)に「メタ情報のプルダウン」を表示するための処理
            $array = ['id' => 1];
            $player_list = User::PlayerSearch($array)->get()->toArray();
            $player_list = array_column($player_list, 'name', 'id');
            $meta_row = array();
            for ($i = 1; $i <= count($s3_contents_array[0]); $i++) {
                $text =
                    "<select name='meta_column' class='select_player form-control form-control-sm pl-0'>
                            <option value='----'>----</option>
                            <option value='putter'>putter</option>
                            <option value='yard'>yard</option>
                            <option value='par'>par</option>'";
                foreach ($player_list as $key => $val) {
                    $text .= "<option value=" . $key . ">" . $val . "</option>";
                }
                $text .= '</select> ';
                array_push($meta_row, $text);
            }

            array_unshift($s3_contents_array, $meta_row);
            // 表の二行目(ヘッダー)に「−」を表示するための処理
            $delete_btn_column_row = array();
            for ($i = 1; $i <= count($s3_contents_array[0]); $i++) {
                array_push($delete_btn_column_row, "-");
            }
            array_unshift($s3_contents_array, $delete_btn_column_row);

            // 表の一行目(ヘッダー)に「⇔」を表示するための処理
            $header_column_row = array();
            for ($i = 1; $i <= count($s3_contents_array[0]); $i++) {
                array_push($header_column_row, '<i class="fas fa-arrows-alt-h grab"></i>');
            }
            array_unshift($s3_contents_array, $header_column_row);

            $s3_contents = '<div class="table-responsive"><table  id="edit_table" class="draggable sortable text-center table-condensed">';

            $row_no = 0;
            $nine_cnt = 1;
            // 行の展開
            foreach ($s3_contents_array as $row) {
                $class = $row_no > 11 ? '"row_cnt"' : ''; //移動バーとゴミ箱があるので11から
                switch ($row_no) {
                    case 0:
                        $s3_contents .= '<thead><tr class="row_' . $row_no . '"><td></td><td></td>';
                        break;
                    case 1:
                        $s3_contents .= '<tr class="row_' . $row_no . '"><td></td><td></td>';
                        break;
                    case 2:
                        $s3_contents .= '<tr class="row_' . $row_no . '"><td></td><td></td>';
                        break;
                    default:
                        // 削除ボタン
                        // $s3_contents .= '<tr class="row_'. $row_no. '"><td><button class="btn_delete_row btn btn-danger btn-sm" type="button">-</button></td>';
                        $s3_contents .= '<tr class="row_' . $row_no . '"><td class=' . $class . '>' . $nine_cnt . '</td><td><i class="btn_delete_row fas fa-trash-alt" style="cursor: pointer; color: red;"></i></td>';

                        $nine_cnt++;
                        break;
                }
                // 列の展開
                $column_no = 0;
                foreach ($row as $column) {
                    switch ($column) {
                        case '<i class="fas fa-arrows-alt-h grab"></i>':
                            $s3_contents .= '<th class="column_' . $column_no . '">' . $column . '</th>';
                            break;
                        case '-':
                            $s3_contents .= '<th class="column_' . $column_no . '"><i class="btn_delete_column fas fa-trash-alt" style="cursor: pointer; color: red;"></i></th>';
                            break;
                        case '':
                        case (strpos($column, 'select') === 1):
                            $s3_contents .= '<th class="column_' . $column_no . '">' . $column . '</th>';
                            break;
                        default:
                            $s3_contents .= '<td class="column_' . $column_no . '"><input type="text" name="" value="' . $column . '" style="width:50px"></td>';
                            break;
                    }
                    $column_no++;
                }
                $s3_contents .= '</tr>';

                if ($row_no == 2) {
                    $s3_contents .= '</thead><tbody>';
                }
                $row_no++;
            }
            $s3_contents .= '</tbody></table></div>';
            // var_dump($s3_contents);
            // テーブルの下に出すボタン
            $s3_contents .= '<button id="add_row" class="btn btn-info" type="button" style="margin:3px">行を追加</button>';
            $s3_contents .= '<button id="add_column" class="btn btn-info" type="button" style="margin:3px">列を追加</button>';
            $s3_contents .= '<button id="reload_table" class="btn btn-info" type="button" style="margin:3px">再読込み</button>';
            $s3_contents .= '<button id="save_btn" class="btn btn-success" type="button" style="margin:3px">保存</button>';
            // -------------------------【終】テーブル表示のための加工-------------------------
        } else {
            $s3_contents = '対象のファイルは存在しません';
        }

        return response()->json(compact('s3_contents'));
    }
}
