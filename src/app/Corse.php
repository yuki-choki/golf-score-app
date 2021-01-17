<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corse extends Model
{
    // 意図的に更新しないカラムを指定
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopeSearchCorse($query, $array)
    {
        if ($array['pref_code'] !== '0') {
            $query->where('pref_code', $array['pref_code']);
        }
        if ($array['name']) {
            $query->where('name', 'like', '%'. $array['name'] .'%');
        }

        return $query;
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
