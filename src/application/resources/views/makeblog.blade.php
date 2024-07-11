<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規ブログ作成画面</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/app.css">
    <style>
        #btn {
            margin-left: 380px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar">
        @include('sidebar')  
    </div>
    <div class="body">
        <div class="d-flex align-items-center justify-content-center" height="auto">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">    
                @csrf
                <h1 class="text-center"> ブログ作成</h1><br>
                <p>カテゴリ：<input name="category" type="text" required>
                <p>タイトル：<input name="title" type="text" required>
                <p>サムネイル： <input name="thumnail" type="file" accept=".jpg,.jpeg,.png" required onchange="previewImage(this);">
                <br>
                <p class="text-center">
                    <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;">
                </p>
                <p>本文：<br><textarea name="content" cols="50" rows="30" required></textarea>
                <p><input type="submit" id="btn" class="btn btn-primary" value="登録">
            </div>
        </form>
        </div>
    </div>
</div>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>


