<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </style>
    @stack('css') {{-- ページ固有のCSSが必要な場合 --}}
</head>
<body>
    <div class="container-fluid p-0 d-flex main-content">
        @auth
            <div class="sidebar bg-dark text-white" style="width: 250px;">
                @include('sidebar')
            </div>
        @endauth

        <div class="flex-grow-1 overflow-auto">
            {{-- ヘッダー (共通パーツ) --}}
            <header class="bg-primary text-white p-3 shadow-sm text-center">
                <h1 class="h4 m-0">@yield('header_title', 'Blog App')</h1>
            </header>

            {{-- 各ページの中身がここに注入される --}}
            <main class="p-4">
                @yield('content')
            </main>

            {{-- フッター (共通パーツ) --}}
            <footer class="text-center p-3 text-muted border-top">
                &copy; {{ date('Y') }} TOKO-BLOG
            </footer>
        </div>
    </div>

    {{-- 共通のJS --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        function previewImage(obj)
        {
            var fileReader = new FileReader();
            fileReader.onload = (function() {
                document.getElementById('preview').src = fileReader.result;
            });
            fileReader.readAsDataURL(obj.files[0]);
        }
    </script>
</body>
</html>