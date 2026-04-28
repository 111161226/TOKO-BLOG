@extends('layouts.app')

@section('title', 'Signup')
@section('header_title', '新規登録')

@push('css')
    <style>
        #head {
            text-align : center;
        }
        #btn {
            margin-left: 200px;
        }
        #lnk {
            margin-left: 80px;
        }
        #preview {
            border-radius: 50%;  /* turn into radius */
            width:  200px;       /* set width */
            height: 180px;       /* set height */
        } 
    </style>
@endpush

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                @csrf   
                <div>
                    <label>
                        ユーザー名：
                        <input type="text" name="username" required>
                    </label>
                </div>
                <div>
                    <label>
                        パスワード：
                        <input type="password" name="pass" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30" required>
                    </label>
                </div>
                <div>
                    <p>
                        サムネイル：
                        <input name="thumnail" type="file" accept=".jpg,.jpeg,.png" required onchange="previewImage(this);">
                    <p class="text-center">
                        <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                    </p>
                </div>
                <input type="submit" id="btn" class="btn btn-primary" value="新規登録">
                @if ($errors->any())
                <div class="invalid-feedback d-block">
                    @foreach ($errors->all() as $error)
                        <p> {{ $error }} </p>
                    @endforeach
                </div>
                @endif
                <br>
                <p id="lnk">すでに登録済みの方は<a href="/login">こちら</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
