<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/'); 
        }
        $err_msgs = [''];
        return view('login', compact('err_msgs'));
    }

    public function login(Request $request)
    {
        // 1. バリデーション
        $credentials = [
            'user_name' => $request->username,
            'password'  => $request->pass,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        $err_msg = 'ユーザー名またはパスワードが間違っています。';
        return back()->withErrors(compact('err_msg'));
    }

    public function logout(Request $request)
    {
        // 1. Laravelの認証システムからログアウトさせる
        Auth::logout();

        // 2. 現在のセッションデータをすべてクリアする (session_destroyに近い)
        $request->session()->invalidate();

        // 3. CSRFトークンを再生成する (セキュリティ上の定石)
        $request->session()->regenerateToken();

        // 4. ログイン画面へリダイレクト
        return redirect('/login')->with('success', 'ログアウトしました');
    }
}
