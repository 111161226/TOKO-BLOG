@extends('layouts.app') {{-- 親レイアウトを指定 --}}

@section('title', 'Login')
@section('header_title', 'ログインページ')

@push('css')
    <style>
        #btn {
            margin-left: 200px;
        }
        #lnk {
            margin-left: 120px;
        }
    </style>
@endpush

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>ユーザー名： <input type="text" name="username" required></label>

                        <label> パスワード： <input type="password" name="pass" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30" required> </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">ログイン</button>
                    @if ($errors->any())
                        <div class="invalid-feedback d-block">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <br>
                    <p id="lnk">新規登録する方は<a href="/register">こちら</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
