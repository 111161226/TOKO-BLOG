<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ログイン中のユーザーIDを取得
        $userId = Auth::id();

        // home.blade.php にあったSQLをクエリビルダで再現
        $images = DB::table('images')
            ->select('images.image_id', 'images.image_name', 'images.image_size')
            ->join('image_owner', 'image_owner.album_id', '=', 'images.image_id')
            ->where('image_owner.author_id', $userId)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('blogs')
                      ->whereRaw('images.image_id = blogs.thumnail_id');
            })
            ->orderBy('images.created_at', 'desc')
            ->get();

        // データを渡してViewを返す
        return view('home', compact('images'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // DBから特定の画像1件を取得
        $image = DB::table('images')
            ->where('image_id', $id)
            ->first();

        // 画像が存在しない場合は404エラーを出す
        if (!$image || !$image->image_content) {
            abort(404);
        }

        // 画像バイナリを正しいContent-Typeで返す
        return response($image->image_content)
            ->header('Content-Type', $image->image_type);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. バリデーション (2MB制限、jpg/png限定)
        $request->validate([
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'image.*.image' => '画像ファイルを選択してください。',
            'image.*.mimes' => 'jpeg, jpg, png形式のみアップロード可能です。',
            'image.*.max'   => 'ファイルサイズは2MB以内にしてしてください。',
        ]);

        if ($request->hasFile('image')) {
            $files = $request->file('image');

            // トランザクション開始
            DB::transaction(function () use ($files) {
                foreach ($files as $file) {
                    $imageId = (string) Str::uuid();

                    // 2. imagesテーブルにバイナリデータを保存
                    DB::table('images')->insert([
                        'image_id'      => $imageId,
                        'image_name'    => $file->getClientOriginalName(),
                        'image_type'    => $file->getMimeType(),
                        'image_content' => file_get_contents($file->getRealPath()),
                        'image_size'    => $file->getSize(),
                        'created_at'    => now(),
                    ]);

                    // 3. image_ownerテーブルに所有者（自分）を紐付け
                    DB::table('image_owner')->insert([
                        'album_id'  => $imageId,
                        'author_id' => Auth::id(),
                    ]);
                }
            });

            return redirect('/')->with('success', '画像をアップロードしました');
        }

        return back()->withErrors(['image' => 'ファイルが選択されていません']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Request $request は第2引数に下げるか、使わないなら消してもOK
    public function destroy($id) 
    {
        $userId = Auth::id();

        // 1. 所有権のチェック
        $isOwner = DB::table('image_owner')
            ->where('album_id', $id)
            ->where('author_id', $userId)
            ->exists();

        if (!$isOwner) {
            abort(403, 'この画像を削除する権限がありません。');
        }

        // 2. 削除処理
        DB::transaction(function () use ($id) {
            DB::table('image_owner')->where('album_id', $id)->delete();
            DB::table('images')->where('image_id', $id)->delete();
        });

        return redirect('/')->with('success', '画像を削除しました');
    }
}
