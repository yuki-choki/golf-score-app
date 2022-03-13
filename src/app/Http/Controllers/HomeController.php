<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $games = Game::all();
        $chart_data = [
            ['ラウンド日', 'スコア', 'パット']
        ];
        $average = [
            'score' => 0,
            'putter' => 0,
            'count' => 0
        ];
        $record = [
            'best' => 0,
            'best_date' => '',
            'worst' => 0,
            'worst_date' => ''
        ];
        foreach ($games as $key => $game) {
            // 対象ゲームのユーザーのスコアを取得する
            $score_cards = $game->score_cards->where('user_id', Auth::id());
            $game->total_score = 0;
            $game->total_putter = 0;
            foreach ($score_cards as $score_card) {
                $game->total_score += $score_card->getScoreCount('score');
                $game->total_putter += $score_card->getScoreCount('putter');
            }
            $chart_data[] = [date("'y n/d", strtotime($game->date)), $game->total_score, $game->total_putter];
            // 平均スコア取得
            $average['score'] += $game->total_score;
            $average['putter'] += $game->total_putter;
            $average['count']++;
            // ベストスコア取得
            if ($record['best'] > $game->total_score || $record['best'] === 0) {
                $record['best'] = $game->total_score;
                $record['best_date'] = date("'y n/d", strtotime($game->date));
            }
            // ワーストスコア取得
            if ($record['worst'] < $game->total_score) {
                $record['worst'] = $game->total_score;
                $record['worst_date'] = date("'y n/d", strtotime($game->date));
            }
        }
        if ($average['count']) {
            $average['score'] = round($average['score'] / $average['count'], 1);
            $average['putter'] = round($average['putter'] / $average['count'], 1);
            $record['best'] .= ' (' . $record['best_date'] . ')';
            $record['worst'] .= ' (' . $record['worst_date'] . ')';
        }
        if (strpos($_SERVER['HTTP_REFERER'] ?? '', 'password/reset') !== false) {
            session()->flash('msg_success', 'パスワードをリセットしました');
        }
        return view('home', compact('chart_data', 'average', 'record'));
    }
}
