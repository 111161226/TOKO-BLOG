<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* 画面全体のスクロールを禁止 */
        }

        .wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
            position: relative;
        }

        /* メインエリア：サイドバーの有無に関わらず画面全体を基準にする */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            transition: margin-left 0.4s; /* スライド用 */
            background-color: #f8f9fa;
        }

        /* サイドバーが開いている時の余白 (JSで制御) */
        .main-with-sidebar {
            margin-left: 250px;
        }

        .content-scrollable {
            flex-grow: 1;
            overflow-y: auto; /* コンテンツエリアだけをスクロール */
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
            padding: 2rem;
        }

        footer {
            background-color: #fff;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }

        /* サイドバー内の古いスタイル干渉をリセット */
        .sidebar-wrapper nav ul { list-style: none; padding: 0; }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper">
        @auth
            {{-- sidebar.blade.phpを読み込む --}}
            @include('sidebar')
        @endauth

        {{-- サイドバーの状態に合わせて main-with-sidebar クラスをJSで付け替える --}}
        <div class="main-wrapper {{ Auth::check() ? 'main-with-sidebar' : '' }}" id="main-content">
            {{-- ヘッダー：常に上部に固定 --}}
            <header class="bg-primary text-white p-3 shadow-sm text-center">
                <h1 class="h4 m-0">@yield('header_title')</h1>
            </header>

            {{-- スクロール可能なコンテンツエリア --}}
            <div class="content-scrollable">
                <main>
                    @yield('content')
                </main>
                
                <footer>
                    &copy; {{ date('Y') }} TOKO-BLOG
                </footer>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function previewImage(obj) {
        var fileReader = new FileReader();
        fileReader.onload = (function() {
            // id="preview" のimgタグのsrcを、読み込んだ画像データに置き換える
            document.getElementById('preview').src = fileReader.result;
        });
        fileReader.readAsDataURL(obj.files[0]);
    }
    </script>
    @stack('scripts')
</body>
</html>