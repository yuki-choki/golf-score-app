<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        return view('users.index', compact('user'));
    }

    public function edit(Request $request)
    {
        $user = Auth::user();
        if ($user->isGuest()) {
            session()->flash('msg_error', 'ゲストログインのためプロフィール編集出来ません');
            return redirect('/');
        }
        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $params = $request->all();
        unset($params['_token']);
        $params['update_job'] = 'users/update';
        // プロフィール画像が選択されていればファイル名を変更して DB に保存
        if (isset($params['avatar'])) {
            $filePath = $params['avatar']->store('public');
            // /storage/ はシンボリックリンク。実際のパスは storage/app/public/
            $params['avatar'] = str_replace('public/', '/storage/', $filePath);
        }
        if ($user->fill($params)->save()) {
            session()->flash('msg_success', 'プロフィールを更新しました');
        } else {
            session()->flash('msg_error', 'プロフィールの更新に失敗しました');
        }
        return redirect()->route('users.edit');
    }

    public function getUser()
    {
        return Auth::user();
    }
}