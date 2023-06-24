@include('functions')
<?php
    $pdo = connectDB();
    $err_msg = '';
    
    //get all blogs from db
    try{
        $sql = 'SELECT user_name, image_id FROM `users` INNER JOIN user_thumnail ON u_id = user_id WHERE user_id = :user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();
    } catch(Exception $error){
        echo "failed to get user info" . $error->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style type="text/css">
        #head {
            text-align : center;
            background-color:#1e93c1;
        }
        #btn {
            margin-left: 300px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        @include('sidebar')  
    </div>
    <div class="body">
        <h1 id="head">プロフィール</h1>
        <div class="d-flex align-items-center justify-content-center">
            <form method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group"> 
                    <div>
                        <label>
                            ユーザー名： <input type="text" name="username" value="<?= $user['user_name'];?>">
                        </label>
                    </div>
                    <div>
                        <label>
                            新しいパスワード： <input type="password" name="pass" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30">
                        </label>
                    </div>
                    <p>
                        サムネイル：
                        <input name="thumnail" type="file" accept=".jpg,.jpeg,.png" onchange="previewImage(this);">
                        <p class="text-center">
                            <img id="preview" src="thumnail?id=<?= $user['image_id']; ?>" style="max-width:200px;" height="auto" class="mr-3"> 
                            <img id="preview" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" style="max-width:200px;">
                        </p>
                    </p>
                    <p><input type="submit" id="btn" class="btn btn-primary" value="更新">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- show thumnail ver Enlarge -->
<div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="carousel-inner">
            <img src="thumnail?id=<?= $user['image_id']; ?>" class="d-block w-100">
        </div>
      </div>
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