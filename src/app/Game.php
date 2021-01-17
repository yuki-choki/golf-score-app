<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Game extends Model
{
    // 意図的に更新しないカラムを指定
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public function corse()
    {
        return $this->belongsTo(Corse::class);
    }

    public function score_cards()
    {
        return $this->hasMany(ScoreCard::class);
    }
}
