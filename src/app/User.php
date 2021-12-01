<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    // 意図的に更新しないカラムを指定
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function myFriends()
    {
        $friends = self::where('parent_user_id', '=', Auth::user()['id'])
            ->where('delete_flg', '=', 0)
            ->get();

        return $friends;
    }

    public function isGuest()
    {
        if ($this->name === 'ゲスト' && $this->email === 'guest@gmail.com') {
            return true;
        }
        return false;
    }

    public function scopePlayerSearch($query, $array)
    {
        $query
            ->where('parent_user_id', $array['id'])
            ->where('delete_flg', 0)
            ->orWhere('id',  $array['id']);

        return $query;
    }
}
