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
        $names = [];
        $rows = [];
        // View にテーブル形式で表示するためにデータを形成
        foreach ($game->score_cards as $index => $score) {
            $names[] = $score->player_name;
            for ($i = 1; $i < 22; $i++) {
                switch ($i) {
                    case (config('golf_role.BEFORE_HALF_NUM')):
                        if ($index < 1) {
                            $rows[$i][] = $score->getHalfScoreCount('par', 'out') . ' / ' . $score->getHalfScoreCount('yard', 'out');
                            $rows[$i][] = $score->getHalfScoreCount('score', 'out') . ' (' . $score->getHalfScoreCount('putter', 'out') . ')';
                        } else {
                            $rows[$i][] = $score->getHalfScoreCount('score', 'out');
                        }
                        break;
                    case (config('golf_role.AFTER_HALF_NUM')):
                        if ($index < 1) {
                            $rows[$i][] = $score->getHalfScoreCount('par', 'in') . ' / ' . $score->getHalfScoreCount('yard', 'in');
                            $rows[$i][] = $score->getHalfScoreCount('score', 'in') . ' (' . $score->getHalfScoreCount('putter', 'in') . ')';
                        } else {
                            $rows[$i][] = $score->getHalfScoreCount('score', 'in');
                        }
                        break;
                    case (config('golf_role.TOTAL_NUM')):
                        if ($index < 1) {
                            $rows[$i][] = $score->getScoreCount('par') . ' / ' . $score->getScoreCount('yard');
                            $rows[$i][] = $score->getScoreCount('score') . ' (' . $score->getScoreCount('putter') . ')';
                        } else {
                            $rows[$i][] = $score->getScoreCount('score');
                        }
                        break;
                    default:
                        $col_num = ($i > 10) ? $i - 1 : $i; // Holeが10以上になるとforのindexHole数がズレるためindexから1をマイナス
                        if ($index < 1) {
                            $rows[$i][] = $score->{'par_' . $col_num} . ' / ' . $score->{'yard_' . $col_num};
                            $rows[$i][] = $score->{'score_' . $col_num} . ' (' . $score->{'putter_' . $col_num} . ')';
                        } else {
                            $rows[$i][] = $score->{'score_' . $col_num};
                        }
                        break;
                }
            }
        }
        return view('score.show', compact('game', 'names', 'rows'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('score.edit');
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
        //
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
}
