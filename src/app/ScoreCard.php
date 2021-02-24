<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ScoreCard extends Model
{
    // 意図的に更新しないカラムを指定
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function getScoreCount(string $type)
    {
        $totalCount = 0;
        for ($i = 1; $i < 19; $i++) {
            $columnName = $type . '_' . $i;
            $totalCount += $this->{$columnName};
        }

        return $totalCount;
    }

    public function getHalfScoreCount(string $type, string $inOut)
    {   
        $startHole = $inOut === 'out' ? 1 : 10;
        $halfCount = 0;
        for ($i = $startHole; $i < ($startHole + 9); $i++) {
            $columnName = $type . '_' . $i;
            $halfCount += $this->{$columnName};
        }

        return $halfCount;
    }

    // パター数別の集計処理
    public function putterCount()
    {
        $rtn = [
            'zero_put' => 0,
            'one_put' => 0,
            'two_put' => 0,
            'other' => 0
        ];
        for ($i = 1; $i < 19; $i++) {
            $column_name = 'putter_' . $i;
            switch ($this->{$column_name}) {
                case (0):
                    $rtn['zero_put']++;
                    break;
                case (1):
                    $rtn['one_put']++;
                    break;
                case (2):
                    $rtn['two_put']++;
                    break;
                default:
                    $rtn['other']++;
            }
        }

        return $rtn;
    }

    // スコア別の集計処理
    public function scoreTypeTally()
    {
        $rtn = [
            'eagle' => 0,
            'birdie' => 0,
            'par' => 0,
            'bogey' => 0,
            'd_bogey' => 0,
            'other' => 0
        ];
        for ($i = 1; $i < 19; $i++) {
            $par_column = 'par_' . $i;
            $score_column = 'score_' . $i;
            switch ($this->{$score_column} - $this->{$par_column}) {
                case (-2):
                    $rtn['eagle']++;
                    break;
                case (-1):
                    $rtn['birdie']++;
                    break;
                case (0):
                    $rtn['par']++;
                    break;
                case (1):
                    $rtn['bogey']++;
                    break;
                case (2):
                    $rtn['d_bogey']++;
                    break;
                default:
                    $rtn['other']++;
                    break;
            }
        }

        return $rtn;
    }
}
