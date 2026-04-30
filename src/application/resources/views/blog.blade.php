@extends('layouts.app')

@section('title', isset($blog) ? 'ブログ編集' : 'ブログ作成')
@section('header_title', isset($blog) ? 'ブログ編集' : 'ブログ作成')

@section('content')
{{-- 外枠：Flexboxで確実に中央配置 --}}
<div style="width: 100%; display: flex; justify-content: center; padding: 30px 15px;">
    
    {{-- メインエリア：最大幅を800pxに固定して中央配置 --}}
    <div style="width: 100%; max-width: 800px;">
        
        {{-- 戻るボタン --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ isset($blog) ? route('blog.show', $blog->blog_id) : route('blog.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> {{ isset($blog) ? '戻る' : '一覧に戻る' }}
            </a>
        </div>

        {{-- 入力フォーム本体 --}}
        <div class="card shadow-sm border-0" style="background-color: #fff; border-radius: 10px; overflow: hidden;">
            <div class="card-body" style="padding: 40px;">
                
                <form action="{{ isset($blog) ? route('blog.update', $blog->blog_id) : route('blog.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($blog))
                        @method('PATCH')
                        <input type="hidden" name="tid" value="{{ $blog->thumnail_id }}">
                    @endif

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">カテゴリー</label>
                        <input name="category" type="text" class="form-control form-control-lg" value="{{ $blog->category ?? '' }}" placeholder="例: Vtuber" required>
                    </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">タイトル</label>
                        <input name="title" type="text" class="form-control form-control-lg" value="{{ $blog->title ?? '' }}" placeholder="タイトルを入力" required>
                    </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">サムネイル</label>
                        <input name="thumnail" type="file" class="form-control-file mb-3" accept=".jpg,.jpeg,.png" onchange="previewImage(this);" {{ isset($blog) ? '' : 'required' }}>
                        <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px; border: 1px dashed #ddd;">
                            <img id="preview" src="{{ isset($blog) ? route('images.show', $blog->thumnail_id) : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' }}" 
                                 style="max-width: 100%; max-height: 250px; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label style="font-weight: bold; font-size: 1.1rem;">本文 (Markdown形式)</label>
                        <textarea name="content" class="form-control" rows="15" required style="line-height: 1.6; font-size: 1rem;">{{ $blog->content ?? '' }}</textarea>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" style="font-weight: bold; min-width: 200px;">
                            <i class="fas fa-save"></i> {{ isset($blog) ? '更新する' : '登録する' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection