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
            $builder->where('user_id', Auth::id())->orderBy('date', 'desc');
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

    public function scopeAnalysisSearch($query, $array)
    {
        if ($array['date_from']) {
            $query->where('date', '>=', $array['date_from']);
        }
        if ($array['date_to']) {
            $query->where('date', '<=', $array['date_to']);
        }
        if ($array['corse_id'] !== '0') {
            $query->where('corse_id', $array['corse_id']);
        }

        return $query;
    }
}
