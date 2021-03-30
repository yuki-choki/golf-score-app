<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 各SNSの認証ページヘリダイレクト
     *
     * @param string $social sns service name.
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($social)
    {
        return Socialite::driver($social)->redirect();
    }

    /**
     * 各SNSから返されるユーザー情報を処理
     *
     * @param string $social sns service name.
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($social)
    {
        if (!in_array($social, ['github', 'facebook', 'google', 'twitter'], true)) {
            session()->flash('msg_success', 'ログインしました');
            return redirect()->to('/home');
        }
        try {
            $socialAccount = $social == 'twitter' ?
                Socialite::driver($social)->user() :
                Socialite::driver($social)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/home');
        }
        $where = [
            ['social_name', '=', $social],
            ['social_id', '=', $socialAccount->getId()]
        ];
        $userName = $socialAccount->getNickname() ?? $socialAccount->getName();
        $countUser = DB::table('users')->where($where)->count();

        if ($countUser === 1) {
            ($user = User::where($where)->first())
                ->update([
                    'name' => $userName,
                    'email' => $socialAccount->getEmail(),
                    'avatar' => $socialAccount->getAvatar(),
                    'update_job' => 'login/handleProviderCallback',
                ]);
        } else {
            ($user = new User([
                'name' => $userName,
                'email' => $socialAccount->getEmail(),
                'social_name' => $social,
                'social_id' => $socialAccount->getId(),
                'avatar' => $socialAccount->getAvatar(),
                'update_job' => 'login/handleProviderCallback',
            ]))->save();
        }

        auth()->login($user, true);
        session()->flash('msg_success', 'ログインしました');
        return redirect()->to('/home');
    }

    /**
     * ゲストログイン機能
     * 
     * @return \Illuminate\Http\Response
     */
    public function guestLogin()
    {
        $email = 'guest@gmail.com';
        $password = 'password';
        if (Auth::attempt(compact('email', 'password'))) {
            session()->flash('msg_success', 'ゲストでログインしました');
            return redirect('/');
        }

        session()->flash('msg_error', 'ゲストログインに失敗しました');
        return redirect('/');
    }
}
