@extends('layouts.app')

@section('title', 'ホーム')
@section('header_title', 'アルバム')

@section('content')
<div class="row">
    <div class="col-md-8 border-right">
        <!-- show image -->
        <ul class="list-unstyled">
            @csrf
            @for($i = 0; $i < count($images); $i++)
                <li class="media mt-5">
                    <a href="#lightbox" data-toggle="modal" data-slide-to="<?= $i; ?>">
                        <img src="images/<?= $images[$i]->image_id; ?>" width="100" height="auto" class="mr-3">
                    </a>
                    <div class="media-body">
                    <h5><?= $images[$i]->image_name; ?> (<?= number_format($images[$i]->image_size/1000, 2); ?> KB)</h5>
                        <form action="{{ route('images.destroy', $images[$i]->image_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('本当に削除しますか？')">
                                <i class="far fa-trash-alt"></i>
                                削除
                            </button>
                        </form>
                    </div>
                </li>
            @endfor
        </ul>
    </div>
    <!-- store image -->
    <div class="col-md-4 pt-4 pl-4">
        <form action="{{ route('images.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>画像を選択</label>
                <input type="file" name="image[]" multiple="multiple" accept=".jpg,.jpeg,.png" required>
                @if ($errors->any())
                    <div class="invalid-feedback d-block">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
</div>

<div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <ol class="carousel-indicators">
            <?php for ($i = 0; $i < count($images); $i++): ?>
                <li data-target="#lightbox" data-slide-to="<?= $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
            <?php endfor; ?>
        </ol>

        <div class="carousel-inner">
            <?php for ($i = 0; $i < count($images); $i++): ?>
                <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>">
                <img src="images/<?= $images[$i]->image_id; ?>" class="d-block w-100">
                </div>
            <?php endfor; ?>
        </div>

        <a class="carousel-control-prev" href="#lightbox" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#lightbox" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </div>
</div>
@endsection