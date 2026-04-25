<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('signup');
    }

    public function register(Request $request)
    {
        // 1. バリデーション（重複チェックも自動でできます）
        $request->validate([
            'username' => 'required|unique:users,user_name|max:255',
            'pass' => 'required|min:4',
            'thumnail' => 'required|image|max:2048',
        ]);

        // 2. 登録処理（トランザクションで複数テーブルへの保存を保護）
        DB::transaction(function () use ($request) {
            $userId = (string) Str::uuid();
            $imageId = (string) Str::uuid();

            // ユーザー保存
            DB::table('users')->insert([
                'user_id' => $userId,
                'user_name' => $request->username,
                'password' => Hash::make($request->pass),
                'thumnail_id' => $imageId,
            ]);

            // サムネイル保存
            $file = $request->file('thumnail');
            DB::table('images')->insert([
                'image_id' => $imageId,
                'image_name' => $file->getClientOriginalName(),
                'image_type' => $file->getMimeType(),
                'image_content' => file_get_contents($file->getRealPath()),
                'image_size' => $file->getSize(),
            ]);
        });

        // 3. 完了後の移動
        return redirect('/')->with('success', '登録が完了しました！');
    }
}