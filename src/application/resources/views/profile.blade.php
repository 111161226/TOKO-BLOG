@extends('layouts.app')

@section('title', 'Profile')
@section('header_title', 'プロフィール設定')

@push('css')
<style>
    /* プレビュー画像自体をボタンにするスタイル */
    .image-upload-wrapper {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    .image-upload-wrapper:hover #preview {
        opacity: 0.7; /* ホバー時に少し暗くして「押せる」感を出す */
    }
    .image-upload-wrapper::after {
        content: '変更';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        background: rgba(0,0,0,0.5);
        padding: 5px 10px;
        border-radius: 5px;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .image-upload-wrapper:hover::after {
        opacity: 1;
    }
    #preview {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #007bff;
        transition: opacity 0.3s;
    }
</style>
@endpush

@section('content')
<div class="profile-card">
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="text-center mb-4">
                    {{-- 画像を包むラッパーをクリックしたら、隠した input が反応するようにする --}}
                    <div class="image-upload-wrapper" onclick="document.getElementById('profileImage').click();">
                        <img id="preview" src="{{ route('images.show', $user['thumnail_id']) }}" alt="現在のサムネイル">
                        
                        {{-- 実際の入力枠は hidden にして隠す --}}
                        <input type="file" name="thumnail" id="profileImage" accept=".jpg,.jpeg,.png" style="display: none;" onchange="previewImage(this);">
                    </div>
                </div>

                {{-- 他の入力項目 --}}
                <div class="form-group text-center mb-4">
                    <label>
                        ユーザー名： <input type="text" name="username" value="{{ old('username', $user['user_name']) }}" required>
                    </label>
                    <br>
                    <label>
                        現在のパスワード： <input type="password" name="current_password" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30">
                    </label>
                    <br>
                    <label>
                        新しいパスワード： <input type="password" name="new_password" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30">
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-4">更新</button>
            </form>
        </div>
    </div>
</div>
@endsection