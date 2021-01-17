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
}
