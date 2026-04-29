@extends('layouts.app')

@section('title', 'ブログ一覧')
@section('header_title', 'マイブログ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 border-right">
            <div class="row">
                @forelse ($blogs as $i => $blog)
                    <div class="col-6 col-sm-4 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm border-0 bg-light">
                            {{-- 画像クリックでモーダル起動 --}}
                            <a href="#lightbox" data-toggle="modal" data-slide-to="{{ $i }}" class="d-block">
                                <div class="card-img-top-wrapper" style="height: 150px; overflow: hidden; background: #ddd;">
                                    <img src="{{ route('images.show', $blog->thumnail_id) }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </a>
                            <div class="card-body p-2 text-center">
                                <h3 class="h6 font-weight-bold text-truncate mb-1">
                                    <a href="{{ route('blog.show', $blog->blog_id) }}" class="text-dark">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <form action="{{ route('blog.destroy', $blog->blog_id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('本当に削除しますか？')">
                                        <i class="far fa-trash-alt"></i> 削除
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">ブログはありません</div>
                @endforelse
            </div>
        </div>

        <div class="col-md-3 pt-4 pl-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body text-center">
                    <a href="{{ route('blog.create') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-pen"></i> ブログを書く
                    </a>
                    <p class="small text-muted mt-3">新しい記事を作成して公開しましょう。</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <div id="lightboxCarousel" class="carousel slide" data-ride="false" data-interval="false">
                    <div class="carousel-inner">
                        @foreach($blogs as $i => $blog)
                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                <img src="{{ route('images.show', $blog->thumnail_id) }}" class="img-fluid custom-modal-img shadow-lg">
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev custom-arrow" href="#lightboxCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next custom-arrow" href="#lightboxCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
.card-img-top-wrapper img { transition: transform 0.3s; }
.card-img-top-wrapper img:hover { transform: scale(1.05); }

.custom-modal-img {
    max-height: 70vh;
    object-fit: contain;
    border: 2px solid #fff;
    background-color: #000;
}

.custom-arrow { width: 10%; opacity: 0.8; }
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: rgba(0,0,0,0.6);
    border-radius: 50%;
    padding: 20px;
}
.modal-backdrop.show { opacity: 0.85 !important; }
</style>
@endpush
@endsection