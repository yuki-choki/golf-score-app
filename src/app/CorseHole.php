<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorseHole extends Model
{
    // 意図的に更新しないカラムを指定
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
