<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();

        $blogs = DB::table('blogs')
            ->join('blog_owner', 'blogs.blog_id', '=', 'blog_owner.b_id')
            ->where('blog_owner.author_id', $userId)
            ->select('blogs.blog_id', 'blogs.title', 'blogs.thumnail_id')
            ->orderBy('blogs.created_at', 'desc')
            ->get();

        return view('bloglist', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('makeblog');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string', // フォームからはカテゴリ名が来ると想定
            'thumnail' => 'required|image|max:2048',
        ]);

        $userId = Auth::id();
        $blogId = (string) Str::uuid();
        $thumbnailId = (string) Str::uuid();

        DB::transaction(function () use ($request, $userId, $blogId, $thumbnailId) {
            $file = $request->file('thumnail');

            // --- カテゴリIDの取得または作成 ---
            // 既存のカテゴリを探す
            $category = DB::table('category_list')
                ->where('category', $request->category)
                ->first();

            if ($category) {
                $categoryId = $category->c_id; // 既存のID
            } else {
                // 新規登録してIDを取得
                $categoryId = DB::table('category_list')->insertGetId([
                    'category' => $request->category,
                ]);
            }

            // --- サムネイル保存 ---
            DB::table('images')->insert([
                'image_id' => $thumbnailId,
                'image_name' => $file->getClientOriginalName(),
                'image_type' => $file->getMimeType(),
                'image_content' => file_get_contents($file->getRealPath()),
            ]);

            // --- ブログ保存 (category_id を入れる) ---
            DB::table('blogs')->insert([
                'blog_id' => $blogId,
                'title' => $request->title,
                'content' => $request->content,
                'c_id' => $categoryId,
                'thumnail_id' => $thumbnailId,
            ]);

            // ブログ所有者登録
            DB::table('blog_owner')->insert([
                'b_id' => $blogId,
                'author_id' => $userId,
            ]);
        });

        return redirect()->route('blog.show', ['blog' => $blogId])->with('success', 'ブログを公開しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  Str  $blob
     * @return \Illuminate\Http\Response
     */
    public function show($blob)
    {
        // blogs, blog_owner, users の3つを結合して取得
        $blog = DB::table('blogs')
            ->join('blog_owner', 'blogs.blog_id', '=', 'blog_owner.b_id')
            ->join('users', 'blog_owner.author_id', '=', 'users.user_id')
            ->join('category_list', 'blogs.c_id', '=', 'category_list.c_id') // カテゴリ名も出す場合
            ->where('blogs.blog_id', $blob)
            ->select(
                'blogs.*', 
                'users.user_id as author_id',
                'users.user_name as author_name', 
                'users.thumnail_id as author_thumnail',
                'category_list.category as category_name'
            )
            ->first();

        if (!$blog) {
            abort(404, 'ブログが見つかりません');
        }

        $userId = Auth::id();

        return view('showblog', compact('blog', 'userId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Str  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($blog)
    {
        $blog = DB::table('blogs')
            ->join('blog_owner', 'blogs.blog_id', '=', 'blog_owner.b_id')
            ->join('category_list', 'blogs.c_id', '=', 'category_list.c_id') 
            ->where('blogs.blog_id', $blog)
            ->where('blog_owner.author_id', Auth::id())
            ->select('blogs.*', 'category_list.category as category')
            ->first();

        if (!$blog) {
            abort(404, 'ブログが見つかりません');
        }

        return view('editblog', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Str  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $blog)
    {
        // 1. バリデーション
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'thumnail' => 'nullable|image|max:2048', // 更新時は任意
        ]);

        $userId = Auth::id();

        // 2. 権限チェック
        $isOwner = DB::table('blog_owner')
            ->where('b_id', $blog)
            ->where('author_id', $userId)
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        DB::transaction(function () use ($request, $blog) {
            // a. カテゴリIDの解決 (以前のstoreと同様)
            $category = DB::table('category_list')
                ->where('category', $request->category)
                ->first();

            $categoryId = $category 
                ? $category->c_id 
                : DB::table('category_list')->insertGetId([
                    'category' => $request->category,
                ]);

            $blogUpdateData = [
                'title' => $request->title,
                'content' => $request->content,
                'c_id' => $categoryId,
                'updated_at' => now(),
            ];

            // c. サムネイルがアップロードされた場合
            if ($request->hasFile('thumnail')) {
                $file = $request->file('thumnail');
                
                // 現在のブログ情報を取得して thumbnail_id を特定
                $currentBlog = DB::table('blogs')->where('blog_id', $blog)->first();
                
                // images テーブルの中身だけ更新 (ID固定)
                DB::table('images')
                    ->where('image_id', $currentBlog->thumnail_id)
                    ->update([
                        'image_name' => $file->getClientOriginalName(),
                        'image_type' => $file->getMimeType(),
                        'image_content' => file_get_contents($file->getRealPath()),
                    ]);
            }

            // d. ブログテーブルの更新
            DB::table('blogs')
                ->where('blog_id', $blog)
                ->update($blogUpdateData);
        });

        return redirect()->route('blog.show', ['blog' => $blog])->with('success', 'ブログを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Str  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($blog)
    {
        $userId = Auth::id();

        // 1. 削除権限のチェック (オリジナルコードの $num == 0 に相当)
        $isOwner = DB::table('blog_owner')
            ->where('b_id', $blog)
            ->where('author_id', $userId)
            ->exists();

        if (!$isOwner) {
            abort(403, '削除権限がないか、ブログが存在しません');
        }

        // 2. トランザクションによる一括削除
        DB::transaction(function () use ($blog) {
            // 削除対象のサムネイルIDを取得しておく
            $bl = DB::table('blogs')->where('blog_id', $blog)->first();
            $thumbnailId = $bl->thumnail_id;

            // a. 画像の所有者情報を削除
            DB::table('image_owner')->where('album_id', $thumbnailId)->delete();

            // b. サムネイル画像の実体を削除
            DB::table('images')->where('image_id', $thumbnailId)->delete();

            // c. ブログの所有者情報を削除
            DB::table('blog_owner')->where('b_id', $bl->blog_id)->delete();

            // d. ブログ本体を削除
            DB::table('blogs')->where('blog_id', $bl->blog_id)->delete();
        });

        return redirect()->route('blog.index')->with('success', 'ブログを削除しました');
    }

    public function search(Request $request)
    {
        $userId = Auth::id();
        $title = $request->query('title');
        $category = $request->query('category');
        
        $blogs = [];

        // 何かキーワードが入っている時だけ検索を実行
        if ($request->filled('title') || $request->filled('category')) {
            $query = DB::table('blogs')
                ->join('category_list', 'blogs.c_id', '=', 'category_list.c_id')
                ->join('blog_owner', 'blogs.blog_id', '=', 'blog_owner.b_id')
                ->join('users', 'blog_owner.author_id', '=', 'users.user_id')
                ->select(
                    'blogs.*',
                    'users.user_name as author_name',
                    'users.thumnail_id as author_thumnail',
                    'category_list.category as category_name'
                )
                ->where('blog_owner.author_id', '!=', $userId);

            if ($request->filled('title')) {
                $query->where('blogs.title', 'LIKE', "%{$title}%");
            }

            if ($request->filled('category')) {
                $query->where('category_list.category', 'LIKE', "%{$category}%");
            }

            $blogs = $query->orderBy('blogs.created_at', 'desc')->get();
        }

        return view('searchblog', compact('blogs', 'title', 'category'));
    }
}
