@extends('layouts.app')

@section('title', 'Search')
@section('header_title', 'ブログ検索')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">

    {{-- 検索フォーム：中央にコンパクトに配置 --}}
    <div style="background: #f8f9fa; padding: 30px; margin-bottom: 30px; border-radius: 8px; text-align: center; border: 1px solid #ddd;">
        <form method="get" action="{{ route('blog.search') }}" class="form-inline justify-content-center">
            <div class="form-group mx-2">
                <label class="mr-2">タイトル</label>
                <input type="text" name="title" class="form-control" value="{{ $title }}" placeholder="タイトルを入力">
            </div>
            <div class="form-group mx-2">
                <label class="mr-2">カテゴリ</label>
                <input type="text" name="category" class="form-control" value="{{ $category }}" placeholder="カテゴリを入力">
            </div>
            <button type="submit" class="btn btn-primary px-4 ml-2 shadow-sm">
                <i class="fas fa-search"></i> 検索
            </button>
        </form>
    </div>

    {{-- 検索結果：3x3タイルのグリッド --}}
    <div class="row">
        @forelse ($blogs as $i => $blog)
            <div class="col-6 col-sm-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm border-0" style="overflow: hidden;">
                    <a href="#lightbox" data-toggle="modal" data-slide-to="{{ $i }}">
                        <div style="height: 160px; background: #eee;">
                            <img src="{{ route('images.show', $blog->thumnail_id) }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                    </a>
                    <div class="card-body p-2 text-center">
                        <h3 class="h6 font-weight-bold text-truncate mb-2">
                            <a href="{{ route('blog.show', $blog->blog_id) }}" class="text-dark">{{ $blog->title }}</a>
                        </h3>
                        <div class="d-flex align-items-center justify-content-center pt-2 border-top">
                            <img src="{{ route('images.show', $blog->author_thumnail) }}" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
                            <span class="small text-muted text-truncate">{{ $blog->author_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- 結果なしメッセージ --}}
            <div class="col-12 text-center py-5">
                <h4 class="text-secondary"><i class="fas fa-search fa-2x mb-3"></i><br>
                @if(!empty($title) || !empty($category))
                    一致するブログは見つかりませんでした。
                @else
                    キーワードを入力して検索してみましょう！
                @endif
                </h4>
            </div>
        @endforelse
    </div>
</div>

{{-- モーダル部分は変更なし --}}
<div class="modal fade" id="lightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <div id="searchCarousel" class="carousel slide" data-ride="false">
                    <div class="carousel-inner">
                        @foreach($blogs as $i => $blog)
                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                <img src="{{ route('images.show', $blog->thumnail_id) }}" class="img-fluid custom-modal-img shadow-lg">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev custom-arrow" href="#searchCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next custom-arrow" href="#searchCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.custom-modal-img { 
    max-height: 70vh; 
    object-fit: contain; 
    border: 2px solid white; 
    background: #000; 
}
.custom-arrow { 
    width: 10%; 
    opacity: 0.8; 
}
.carousel-control-prev-icon, .carousel-control-next-icon { 
    background-color: rgba(0,0,0,0.6);
    border-radius: 50%; 
    padding: 20px; 
}
</style>
@endpush
@push('scripts')
<script>
$(document).ready(function() {
    // サムネイル画像（aタグ）をクリックしたとき
    $('a[data-toggle="modal"]').on('click', function() {
        // data-slide-to属性から、何番目の画像かを取得
        var slideTo = $(this).attr('data-slide-to');
        // カルーセル（id="searchCarousel"）をその番号まで移動させる
        $('#searchCarousel').carousel(parseInt(slideTo));
    });
});
</script>
@endpush
@endsection