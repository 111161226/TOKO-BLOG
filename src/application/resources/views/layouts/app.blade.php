<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .sidebar-wrapper {
            width: 250px;
            min-width: 250px;
            height: 100%;
            background-color: #343a40;
            overflow-y: auto;
        }

        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden; /* 二重バー防止 */
        }

        .content-scrollable {
            flex-grow: 1;
            overflow-y: auto;
            background-color: #f8f9fa;
        }

        footer {
            background-color: #fff;
            z-index: 10;
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper">
        @auth
            <div class="sidebar-wrapper text-white">
                @include('sidebar')
            </div>
        @endauth

        {{-- メインエリア（ヘッダー + コンテンツ + フッター） --}}
        <div class="main-wrapper">
                {{-- ヘッダー --}}
                <header class="bg-primary text-white p-3 shadow-sm text-center">
                    <h1 class="h4 m-0">@yield('header_title')</h1>
                </header>

                {{-- ★ここを修正：d-flex flex-column を追加し、高さを100%活用する --}}
                <div class="content-scrollable d-flex flex-direction-column">
                    <div class="d-flex flex-column w-100" style="min-height: 100%;">
                        
                        {{-- メインコンテンツ：flex-grow-1 を入れることで、余ったスペースをすべて使い切る --}}
                        <main class="p-4 flex-grow-1">
                            @yield('content')
                        </main>
                        
                        {{-- フッター：mainがスペースを押し広げるので、必ず最下部に配置される --}}
                        <footer class="text-center p-3 text-muted border-top bg-white">
                            &copy; {{ date('Y') }} TOKO-BLOG
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
    @stack('scripts')
</body>
</html>