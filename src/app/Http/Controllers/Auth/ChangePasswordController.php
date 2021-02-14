<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showChangePasswordForm()
    {
        return view('auth/passwords/change');
    }
    
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request->get('password'));
        if ($user->save()) {
            session()->flash('msg_success', 'パスワードを変更しました');
        } else {
            session()->flash('msg_error', 'パスワードの変更に失敗しました');
        }
        return redirect()->route('users');
    }
}
