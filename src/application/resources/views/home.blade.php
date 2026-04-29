@extends('layouts.app')

@section('title', 'ホーム')
@section('header_title', 'アルバム')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9 border-right">
            <div class="row">
                @forelse($images as $i => $image)
                    <div class="col-6 col-sm-4 col-lg-3 mb-4"> {{-- 画面幅に応じて3〜4列 --}}
                        <div class="card h-100 shadow-sm border-0 bg-light">
                            {{-- 画像クリックでモーダル起動 --}}
                            <a href="#lightbox" data-toggle="modal" data-slide-to="{{ $i }}" class="d-block">
                                <div class="card-img-top-wrapper" style="height: 150px; overflow: hidden; background: #ddd;">
                                    <img src="images/{{ $image->image_id }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </a>
                            <div class="card-body p-2 text-center">
                                <p class="small text-truncate mb-1" title="{{ $image->image_name }}">{{ $image->image_name }}</p>
                                <form action="{{ route('images.destroy', $image->image_id) }}" method="POST">
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
                    <div class="col-12 text-center py-5 text-muted">画像がありません</div>
                @endforelse
            </div>
        </div>

        <div class="col-md-3 pt-4 pl-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title h6">画像を保存</h5>
                    <form action="{{ route('images.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">保存</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lightbox" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <div id="lightboxCarousel" class="carousel slide" data-ride="false" data-interval="false">
                    <div class="carousel-inner">
                        @foreach($images as $i => $image)
                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                <img src="images/{{ $image->image_id }}" class="img-fluid custom-modal-img shadow-lg">
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- 矢印ボタン --}}
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
/* 一覧の画像：サイズを揃えてタイル状にする */
.card-img-top-wrapper img { transition: transform 0.3s; }
.card-img-top-wrapper img:hover { transform: scale(1.05); }

/* モーダル内の画像調整 */
.custom-modal-img {
    max-height: 75vh;
    object-fit: contain;
    border: 2px solid #fff;
    background-color: #000;
}

/* 矢印のデザイン */
.custom-arrow { width: 10%; opacity: 0.8; }
.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: rgba(0,0,0,0.6);
    border-radius: 50%;
    padding: 20px;
}

/* モーダル背景をしっかり暗く */
.modal-backdrop.show { opacity: 0.85 !important; }
</style>
@endpush
@endsection