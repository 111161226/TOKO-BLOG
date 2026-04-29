<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * マイページ（プロフィール表示）
     */
    public function show()
    {
        // ログイン中のユーザー情報を取得
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * プロフィール更新処理
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'thumnail' => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'user_name' => $request->username,
        ];

        // パスワード変更
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => '現在のパスワードが正しくありません']);
            }
            $updateData['password'] = Hash::make($request->new_password);
        }

        // 画像の「更新 (Update)」
        if ($request->hasFile('thumnail')) {
            $file = $request->file('thumnail');
            
            // 既存のIDを使って images テーブルを更新
            DB::table('images')
                ->where('image_id', $user->thumnail_id)
                ->update([
                    'image_name' => $file->getClientOriginalName(),
                    'image_type' => $file->getMimeType(),
                    'image_content' => file_get_contents($file->getRealPath()),
                    'image_size' => $file->getSize(),
                ]);
        }

        // ユーザー情報の更新 (thumnail_id は変えない)
        DB::table('users')
            ->where('user_id', $user->user_id)
            ->update($updateData);

        return redirect('/profile')->with('success', 'プロフィールを更新しました');
    }
}