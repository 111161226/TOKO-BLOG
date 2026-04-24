<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
