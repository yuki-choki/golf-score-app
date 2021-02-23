<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Corse;
use App\ScoreCard;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
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
            $score_card = $game->score_cards->firstWhere('user_id', Auth::id());
            $game->total_score = $score_card->getScoreCount('score');
            $game->total_putter = $score_card->getScoreCount('putter');
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
        if ($request->method() === 'POST') {
            $params = $request->all();
            unset($params['_token']);
            $corse = Corse::find($params['pref_id']);
        }
        
        return view('score.create', compact('corse', 'params', 'request'));
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
            $params = $request->all();
            // post されてきたデータをbase64エンコードする
            $base64_file = base64_encode($params['file']->path());
            // OCR 読み込み処理を記述
            return $base64_file; // 動作確認としてbase64エンコードしたデータを返す
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
        $game = Game::find($id)->first();
        $names = $this->getPlayerName($game);
        $rows = $this->createTableData($game);

        return view('score.show', compact('game', 'rows', 'names'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = Game::find($id)->first();
        $names = $this->getPlayerName($game);
        $rows = $this->createTableData($game);

        return view('score.edit', compact('game', 'names', 'rows'));
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
        $score_data = [];
        $score_data = $params['player_name'];
        foreach ($params['score'] as $hole => $data) {
            foreach ($data as $id => $score) {
                if (is_array($score)) {
                    $score_data[$id]['score_' . $hole] = $score['score'];
                    $score_data[$id]['putter_' . $hole] = $score['putter'];
                } else {
                    $score_data[$id]['score_' . $hole] = $score;
                }
            }
        }
        $score_cards = $game->score_cards;
        foreach ($score_cards as $score) {
            $score->fill($score_data[$score->id]);
            $score->save();
        }
        $game->memo = $params['memo'];
        $game->save();
        session()->flash('msg_success', 'スコアを編集しました');

        return redirect()->route('scores.show', ['score' => $game->id]);
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
                $score_card = $game->score_cards->firstWhere('user_id', Auth::id());
                $game->total_score = $score_card->getScoreCount('score');
                $game->total_putter = $score_card->getScoreCount('putter');
                $put_scores[] = $score_card->putterCount();
                $score_type_tally[] = $score_card->scoreTypeTally();
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

    private function createTableData($game)
    {
        $res = [];
        // View にテーブル形式で表示するためにデータを形成
        foreach ($game->score_cards as $index => $score) {
            for ($i = 1; $i < 22; $i++) {
                switch ($i) {
                    case (config('golf_role.BEFORE_HALF_NUM')):
                        if ($index < 1) {
                            $res['b_half'][$score->id] = $score->getHalfScoreCount('par', 'out') . ' / ' . $score->getHalfScoreCount('yard', 'out');
                            $res['b_half']['score'][$score->id]['score'] = $score->getHalfScoreCount('score', 'out');
                            $res['b_half']['score'][$score->id]['putter'] = $score->getHalfScoreCount('putter', 'out');
                        } else {
                            $res['b_half'][$score->id] = $score->getHalfScoreCount('score', 'out');
                        }
                        break;
                    case (config('golf_role.AFTER_HALF_NUM')):
                        if ($index < 1) {
                            $res['a_half'][$score->id] = $score->getHalfScoreCount('par', 'in') . ' / ' . $score->getHalfScoreCount('yard', 'in');
                            $res['a_half']['score'][$score->id]['score'] = $score->getHalfScoreCount('score', 'in');
                            $res['a_half']['score'][$score->id]['putter'] = $score->getHalfScoreCount('putter', 'in');
                        } else {
                            $res['a_half'][$score->id] = $score->getHalfScoreCount('score', 'in');
                        }
                        break;
                    case (config('golf_role.TOTAL_NUM')):
                        if ($index < 1) {
                            $res['total'][$score->id] = $score->getScoreCount('par') . ' / ' . $score->getScoreCount('yard');
                            $res['total']['score'][$score->id]['score'] = $score->getScoreCount('score');
                            $res['total']['score'][$score->id]['putter'] =  $score->getScoreCount('putter');
                        } else {
                            $res['total'][$score->id] = $score->getScoreCount('score');
                        }
                        break;
                    default:
                        $row_num = ($i > 10) ? $i - 1 : $i; // Holeが10以上になるとforのindexHole数がズレるためindexから1をマイナス
                        if ($index < 1) {
                            $res[$row_num][$score->id] = $score->{'par_' . $row_num} . ' / ' . $score->{'yard_' . $row_num};
                            $res[$row_num]['score'][$score->id]['score'] = $score->{'score_' . $row_num};
                            $res[$row_num]['score'][$score->id]['putter'] = $score->{'putter_' . $row_num};
                        } else {
                            $res[$row_num][$score->id] = $score->{'score_' . $row_num};
                        }
                        break;
                }
            }
        }
        return $res;
    }

    private function getPlayerName($game)
    {
        $names = [];
        foreach ($game->score_cards as $score) {
            $names[$score->id] = $score->player_name;
        }
        return $names;
    }
}
