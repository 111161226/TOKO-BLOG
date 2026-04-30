<?php
    // 前のURLを取得
    $prevUrl = url()->previous();
    // 検索画面(search)から来たか判定
    $isFromSearch = str_contains($prevUrl, 'search');
    
    // 戻り先URLとテキストの設定
    $backUrl = $isFromSearch ? $prevUrl : route('blog.index');
    $backText = $isFromSearch ? '検索結果に戻る' : '一覧に戻る';
?>
@extends('layouts.app')

@section('title', $blog->title)
@section('header_title', 'ブログ')

@section('content')
{{-- 外枠：画面いっぱいに広げた上で中身を中央に寄せる --}}
<div style="width: 100%; display: flex; justify-content: center; padding: 30px 15px;">
    
    {{-- メインエリア：最大幅を1000pxに固定して中央配置 --}}
    <div style="width: 100%; max-width: 1000px;">
        
        {{-- 操作ボタンエリア --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            {{-- 閲覧用戻るボタン --}}
            <a href="{{ $backUrl }}" class="btn btn-outline-secondary shadow-sm" style="white-space: nowrap;">
                <i class="fas fa-arrow-left"></i> {{ $backText }}
            </a>

            {{-- 著者用編集ボタン --}}
            @if ($blog->author_id == $userId)
                <a href="{{ route('blog.edit', $blog->blog_id) }}" class="btn btn-primary shadow-sm" style="white-space: nowrap;">
                    <i class="fas fa-edit"></i> 編集する
                </a>
            @endif
        </div>

        {{-- 記事カード本体 --}}
        <div class="card shadow-sm border-0" style="background-color: #fff; border-radius: 10px; overflow: hidden;">
            <div class="card-body" style="padding: 50px 40px;">
                
                {{-- タイトル --}}
                <h1 style="text-align: center; font-weight: bold; font-size: 2.2rem; margin-bottom: 40px; line-height: 1.4;">
                    {{ $blog->title }}
                </h1>

                {{-- サムネイル：クリックで拡大 --}}
                <div style="text-align: center; margin-bottom: 50px;">
                    <a href="#lightbox" data-toggle="modal">
                        <img src="{{ route('images.show', $blog->thumnail_id) }}" 
                             style="max-width: 100%; max-height: 500px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: zoom-in;">
                    </a>
                </div>

                {{-- 本文 --}}
                <div class="blog-main-content" style="font-size: 1.1rem; line-height: 2; overflow-wrap: anywhere;">
                    {!! Str::markdown($blog->content, ['html_input' => 'escape']) !!}
                </div>

                <hr style="margin: 50px 0; border-top: 1px solid #eee;">

                {{-- 投稿者情報：中央寄せ --}}
                <div style="display: flex; align-items: center; justify-content: center; background: #f9f9f9; padding: 20px; border-radius: 15px;">
                    <span style="color: #666; margin-right: 15px;">投稿者: <strong>{{ $blog->author_name }}</strong></span>
                    <img src="{{ route('images.show', $blog->author_thumnail) }}" 
                         style="width: 55px; height: 55px; border-radius: 50%; object-circle: cover; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                </div>

            </div>
        </div>
    </div>
</div>

{{-- 拡大モーダル --}}
<div class="modal fade" id="lightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <img src="{{ route('images.show', $blog->thumnail_id) }}" 
                     class="img-fluid" 
                     style="max-height: 85vh; border: 3px solid #fff; border-radius: 5px; background: #000;">
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
/* Markdownの微調整 */
.blog-main-content h1, .blog-main-content h2 { 
    margin-top: 30px; 
    margin-bottom: 15px; 
    border-bottom: 1px solid #eee; 
    padding-bottom: 8px; 
}
.blog-main-content p { 
    margin-bottom: 20px; 
}
.blog-main-content img { 
    max-width: 100%; border-radius: 5px; 
}

/* 背景をしっかり暗く */
.modal-backdrop.show { opacity: 0.9 !important; }
</style>
@endpush
@endsection