@extends('layouts.app')

@section('title', isset($blog) ? 'ブログ編集' : 'ブログ作成')
@section('header_title', isset($blog) ? 'ブログ編集' : 'ブログ作成')

@section('content')
{{-- 外枠：Flexboxで確実に中央配置 --}}
<div style="width: 100%; display: flex; justify-content: center; padding: 30px 15px;">
    
    {{-- メインエリア：最大幅を800pxに固定して中央配置 --}}
    <div style="width: 100%; max-width: 800px;">
        
        {{-- 戻るボタン --}}
        <div style="margin-bottom: 20px;">
            <a href="{{ isset($blog) ? route('blog.show', $blog->blog_id) : route('blog.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left"></i> {{ isset($blog) ? '戻る' : '一覧に戻る' }}
            </a>
        </div>

        {{-- 入力フォーム本体 --}}
        <div class="card shadow-sm border-0" style="background-color: #fff; border-radius: 10px; overflow: hidden;">
            <div class="card-body" style="padding: 40px;">
                
                <form action="{{ isset($blog) ? route('blog.update', $blog->blog_id) : route('blog.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @if(isset($blog))
                        @method('PATCH')
                        <input type="hidden" name="tid" value="{{ $blog->thumnail_id }}">
                    @endif

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">カテゴリー</label>
                        <input name="category" type="text" class="form-control form-control-lg" value="{{ $blog->category ?? '' }}" placeholder="例: Vtuber" required>
                    </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">タイトル</label>
                        <input name="title" type="text" class="form-control form-control-lg" value="{{ $blog->title ?? '' }}" placeholder="タイトルを入力" required>
                    </div>

                    <div class="form-group mb-4">
                        <label style="font-weight: bold; font-size: 1.1rem;">サムネイル</label>
                        <input name="thumnail" type="file" class="form-control-file mb-3" accept=".jpg,.jpeg,.png" onchange="previewImage(this);" {{ isset($blog) ? '' : 'required' }}>
                        <div style="background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px; border: 1px dashed #ddd;">
                            <img id="preview" src="{{ isset($blog) ? route('images.show', $blog->thumnail_id) : 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' }}" 
                                 style="max-width: 100%; max-height: 250px; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <div class="d-flex justify-content-between align-items-end mb-2">
                            <label style="font-weight: bold; font-size: 1.1rem; margin: 0;">本文 (Markdown形式)</label>
                            {{-- 画像挿入用：Albumの画像URLをコピーして使う想定のヘルプ --}}
                            <small class="text-muted">※Albumの画像を `![説明](URL)` で挿入できます</small>
                        </div>

                        {{-- 簡易ツールバー --}}
                        <div class="btn-toolbar mb-2 bg-light p-1 border rounded" role="toolbar">
                            <div class="btn-group mr-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('**', '**')" title="太字"><i class="fas fa-bold"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('# ', '')" title="見出し"><i class="fas fa-heading"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('[', '](url)')" title="リンク"><i class="fas fa-link"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('![説明](', ')')" title="画像挿入"><i class="fas fa-image"></i></button>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('- ', '')" title="箇条書き"><i class="fas fa-list-ul"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertText('`', '`')" title="コード"><i class="fas fa-code"></i></button>
                            </div>
                        </div>

                        {{-- 入力とプレビューの並列レイアウト --}}
                        <div class="row m-0 border rounded overflow-hidden" style="background: #fff;">
                            {{-- エディタ側 --}}
                            <div class="col-md-6 p-0 border-right">
                                <textarea id="markdown-editor" name="content" class="form-control border-0 rounded-0" rows="18" 
                                        style="line-height: 1.6; font-size: 1rem; resize: none;" 
                                        placeholder="ここに内容を書いてください..." required>{{ $blog->content ?? '' }}</textarea>
                            </div>
                            {{-- プレビュー側 --}}
                            <div class="col-md-6 p-3 bg-light overflow-auto" style="height: 18rem * 1.6;">
                                <div class="small text-muted mb-2 border-bottom pb-1">リアルタイムプレビュー</div>
                                <div id="markdown-preview" class="blog-content shadow-none p-0" style="font-size: 0.9rem; line-height: 1.6;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center;">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm" style="font-weight: bold; min-width: 200px;">
                            <i class="fas fa-save"></i> {{ isset($blog) ? '更新する' : '登録する' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const editor = document.getElementById('markdown-editor');
    const preview = document.getElementById('markdown-preview');

    // 1. リアルタイムプレビュー機能
    function updatePreview() {
        // marked.js を使用してHTMLに変換。セキュリティのためsanitize設定を推奨（ライブラリ仕様により要確認）
        preview.innerHTML = marked.parse(editor.value);
    }

    // 入力するたびにプレビューを更新
    editor.addEventListener('input', updatePreview);

    // 読み込み時に初期実行
    document.addEventListener('DOMContentLoaded', updatePreview);

    // 2. テキスト挿入機能 (ツールバー用)
    function insertText(before, after) {
        const start = editor.selectionStart;
        const end = editor.selectionEnd;
        const text = editor.value;
        const selected = text.substring(start, end);
        
        // 選択範囲を挟み込む
        const newText = text.substring(0, start) + before + selected + after + text.substring(end);
        editor.value = newText;
        
        // カーソル位置を調整
        editor.focus();
        editor.setSelectionRange(start + before.length, start + before.length + selected.length);
        
        // 手動でinputイベントを発火させてプレビューを更新
        updatePreview();
    }
</script>
@endpush
@push('css')
<style>
#markdown-preview img {
    max-width: 100%;    /* 親要素の幅に合わせて自動縮小 */
    height: auto;       /* 縦横比を維持 */
    display: block;     /* 余計な隙間を消す */
    margin: 10px auto;  /* 中央寄せ */
    border-radius: 5px; /* 少し角を丸くして見栄えを良く */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* 軽い影をつけて見やすく */
}

/* プレビュー領域自体のスクロールバーを細くする（お好みで） */
#markdown-preview-container {
    scrollbar-width: thin;
}
</style>
@endpush
@endsection