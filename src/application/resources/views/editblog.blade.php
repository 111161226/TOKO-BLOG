@include('functions')
<?php
    $pdo = connectDB();
    $err_msg = '';
    
    //get a blog from db
    try {
        $sql = 'SELECT blog_id, title, content, category, thumnail_id FROM `blogs` INNER JOIN `category_list` ON blogs.`c_id` = `category_list`.`c_id`  WHERE blog_id = :blog_id AND exists (
            SELECT * from blog_owner WHERE blog_id = b_id AND author_id = :uid)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
        $stmt->bindValue(':uid', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->execute();
        $blog = $stmt->fetch();
    } catch (Exception $error) {
        echo "can't get blog" . $error->getMessage();
        exit();
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ブログ更新画面</title>
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
    <div class="body bg-success">
        <div class="d-flex align-items-center justify-content-center" height="auto">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">    
                @csrf
                <h1 class="text-center"> ブログ編集</h1><br>
                <input type="hidden" name="id" value="<?= $blog['blog_id'];?>">
                <input type="hidden" name="tid" value="<?= $blog['thumnail_id'];?>">
                <p>新しいカテゴリ：<input name="category" type="text" value="<?= $blog['category']; ?>" required>
                <p>新しいタイトル：<input name="title" type="text"  value="<?= $blog['title'];?>" required>
                <p>新しいサムネイル： <input name="thumnail" type="file" accept=".jpg,.jpeg,.png" onchange="previewImage(this);">
                <br>

                <p class="text-center">
                    <img id="preview" src="image?id=<?= $blog['thumnail_id']; ?>" style="max-width:200px;" height="auto" class="mr-3"> 
                    <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;"> 
                </p>
                <p>本文：<br><textarea name="content" cols="50" rows="50" required><?= $blog['content']; ?></textarea>
                <p><input type="submit" id="btn" class="btn btn-primary" value="更新">
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