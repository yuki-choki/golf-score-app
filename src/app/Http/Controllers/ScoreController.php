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
        if ($request->method() === 'POST') {
            $params = $request->all();
            unset($params['_token']);
            $searchData = Corse::SearchCorse($params)->get();
        }
        return view('score.search', compact('searchData', 'params', 'request'));
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
            unset($params['_token']);
            // OCR 読み込み処理を記述
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