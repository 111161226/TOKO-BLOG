@include('functions')
<?php
    $pdo = connectDB();
    $err_msg = '';
    
    //get all blogs from db
    try {
        $sql = 'SELECT * FROM `blogs` WHERE blog_id = :blog_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
        $stmt->execute();
        $blog = $stmt->fetch();
    } catch (Exception $error) {
        echo "can't get all blogs" . $error->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ブログ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 border-right">
            <!-- show blog -->
            <h1> <?= $blog['title']; ?> </h1>
        <!--    <ul class="list-unstyled"> -->
                @csrf
                
            <a href="#lightbox" data-toggle="modal">
                <img src="image?id=<?= $blog['thumnail_id']; ?>" width="300" height="auto" class="mr-3">
            </a>
            <div class="media-body">
            <p> <?= $blog['content']; ?>
            </div>
                
            <!-- </ul> -->
        </div>
        <!-- add article -->
        <div class="col-md-4 pt-4 pl-4">
            <button onclick="location.href='/eblog?id=<?= $blog['blog_id']; ?>'" class="btn btn-primary">編集</button>
            <button onclick="location.href='/lblog'" class="btn btn-link">一覧に戻る</button>
        </div>
    </div>
</div>

<!-- show thumnail ver Enlarge -->
<div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="carousel-inner">
            <img src="image?id=<?= $blog['thumnail_id']; ?>" class="d-block w-100">
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>