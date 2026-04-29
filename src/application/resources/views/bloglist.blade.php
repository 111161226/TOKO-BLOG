@extends('layouts.app')

@section('title', 'ブログ一覧')
@section('header_title', 'マイブログ')

@section('content')
<div class="row">
    <div class="col-md-10 border-right">
        <ul class="list-unstyled">
            @forelse ($blogs as $i => $blog)
                <li class="media mb-3 p-3 bg-white shadow-sm rounded border">
                    <a href="#lightbox" data-toggle="modal" data-slide-to="{{ $i }}">
                        <img src="{{ route('images.show', $blog->thumnail_id) }}" width="80" class="mr-3 rounded shadow-sm">
                    </a>
                    <div class="media-body">
                        <h3 class="h5 font-weight-bold"> 
                            <a href="{{ route('blog.show', $blog->blog_id) }}" class="text-dark">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <form action="{{ route('blog.destroy', $blog->blog_id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('本当に削除しますか？')">
                                <i class="far fa-trash-alt"></i> 削除
                            </button>
                        </form>
                    </div>
                </li>
            @empty
                <div class="text-center py-5">
                    <h4 class="text-muted">ブログはありません</h4>
                </div>
            @endforelse
        </ul>
    </div>

    <div class="col-md-2 pt-5 pl-4 text-center">
        <a href="{{ route('blog.create') }}" class="btn btn-primary shadow-sm px-3">
            <i class="fas fa-plus"></i> 追加
        </a>
    </div>
</div>

{{-- 拡大表示用のモーダル --}}
<div class="modal fade" id="lightbox" tabindex="-1" role="dialog" aria-hidden="true">
    {{-- 背景クリックで閉じるため、dialog自体に data-dismiss を付与 --}}
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" data-dismiss="modal" style="cursor: zoom-out;">
        <div class="modal-content bg-transparent border-0">
            
            <div class="text-center mb-1 pr-5">
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="font-size: 2.5rem; opacity: 1; outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                {{-- 画像本体：クリックしても閉じないよう stopPropagation を設定 --}}
                <div id="innerCarousel" class="carousel slide" data-ride="false" onclick="event.stopPropagation();" style="cursor: default;">
                    <div class="carousel-inner">
                        @foreach($blogs as $i => $blog)
                            <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                                <img src="{{ route('images.show', $blog->thumnail_id) }}" class="d-block mx-auto img-fluid shadow-lg" style="max-height: 80vh; object-fit: contain;">
                            </div>
                        @endforeach
                    </div>

                    <a class="carousel-control-prev" href="javascript:void(0)" role="button" data-target="#innerCarousel" data-slide="prev" onclick="event.stopPropagation();">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next" href="javascript:void(0)" role="button" data-target="#innerCarousel" data-slide="next" onclick="event.stopPropagation();">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection