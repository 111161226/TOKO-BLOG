<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
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
            return redirect()->intended('home');
        }

        $err_msg = 'ユーザー名またはパスワードが間違っています。';
        return back()->withErrors(compact('err_msg'));
    }
}
