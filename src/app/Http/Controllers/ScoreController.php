<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\Corse;
use App\ScoreCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            // 保存先を S3 に変更
            Storage::disk('local')->put('public/' . $fileName, $base64_image);
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
}
