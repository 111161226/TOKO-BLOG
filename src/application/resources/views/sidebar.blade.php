<div id="mySidebar" class="sidebar shadow-lg">
    {{-- ヘッダー部分：×ボタンの高さを抑えてメニューを上に詰める --}}
    <div class="sidebar-header d-flex justify-content-end p-2">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </a>
    </div>
    
    <nav>
        {{-- mt-0 に変更して上部余白を削減 --}}
        <ul class="nav flex-column mt-0">
            <li class="nav-item">
                <a href="/" class="nav-link slide-link">
                    <i class="fas fa-images mr-3"></i> Album
                </a>
            </li>
            <li class="nav-item">
                <a href="/blog/search" class="nav-link slide-link">
                    <i class="fas fa-search mr-3"></i> Search
                </a>
            </li>
            <li class="nav-item">
                <a href="/blog" class="nav-link slide-link">
                    <i class="fas fa-blog mr-3"></i> Blog
                </a>
            </li>
            <li class="nav-item">
                <a href="/profile" class="nav-link slide-link">
                    <i class="fas fa-user mr-3"></i> Profile
                </a>
            </li>
            
            {{-- 区切り線の上の余白も調整 --}}
            <li class="nav-item border-top mt-3 pt-3 opacity-75">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <a href="javascript:void(0);" class="nav-link slide-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</div>

{{-- 開くボタン：ヘッダーの中に違和感なく収まるフラットなデザインに修正 --}}
<button id="openBtn" class="openbtn-minimal" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<style type="text/css">
    .sidebar {
        height: 100vh;
        width: 260px;
        position: fixed;
        z-index: 1050;
        top: 0;
        left: 0;
        background-color: #212529;
        transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-x: hidden;
        border-right: 1px solid #343a40;
    }

    /* メニュー項目のパディングを少し詰め、高さを出す */
    .sidebar .nav-link {
        padding: 12px 25px;
        color: #adb5bd !important;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        transition: 0.3s;
    }
    
    .sidebar .nav-link:hover {
        color: #fff !important;
        background-color: #343a40;
    }

    /* ×ボタン周りの余白を最小限に */
    .sidebar-header {
        line-height: 1;
    }
    .closebtn {
        color: #6c757d;
        font-size: 1.8rem;
        text-decoration: none !important;
        padding: 5px 15px;
        transition: 0.3s;
    }

    /* ハンバーガーボタン：青いヘッダーに馴染ませる */
    .openbtn-minimal {
        position: fixed;
        top: 12px;
        left: 15px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.15); /* 白の半透明で馴染ませる */
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        width: 40px;
        height: 40px;
        display: none; /* 初期はサイドバーが開いているので非表示 */
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        cursor: pointer;
    }
    .openbtn-minimal:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .sidebar-closed {
        left: -260px !important;
    }
</style>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("mySidebar");
    const main = document.getElementById("main-content");
    const openBtn = document.getElementById("openBtn");

    if (sidebar.classList.contains("sidebar-closed")) {
        sidebar.classList.remove("sidebar-closed");
        if(main) main.classList.add("main-with-sidebar");
        openBtn.style.display = "none";
    } else {
        sidebar.classList.add("sidebar-closed");
        if(main) main.classList.remove("main-with-sidebar");
        openBtn.style.display = "flex"; // 中央揃えのため flex
    }
}
</script>