@include('functions')
<?php
    $pdo = connectDB();
    $err_msg = '';
    
    //get all blogs from db
    try{
        $sql = 'SELECT `blog_id`, `title`, `thumnail_id` FROM `blogs` INNER JOIN blog_owner ON b_id = blog_id WHERE author_id = :user_id ORDER BY `created_at` DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_STR);
        $stmt->execute();
        $blogs = $stmt->fetchAll();
    } catch(Exception $error){
        echo "failed to get blogs" . $error->getMessage();
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ブログ一覧</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style type="text/css">
        #head {
            text-align : center;
            background-color:#1e93c1;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        @include('sidebar')  
    </div>
    <div class="body">
        <div class="row">
            <div class="col-md-8 border-right">
                <!-- show blog -->
                <h1 id="head"> ブログ一覧 </h1>
                <?php if (count($blogs) == 0): ?>
                    <h4> ブログはありません </h4>
                <?php endif; ?>
                <ul class="list-unstyled">
                    @csrf
                    <?php for ($i = 0; $i < count($blogs); $i++): ?>
                        <li class="media mt-5">
                            <a href="#lightbox" data-toggle="modal" data-slide-to="<?= $i; ?>">
                                <img src="image?id=<?= $blogs[$i]['thumnail_id']; ?>" width="80" height="auto" class="mr-3">
                            </a>
                            <div class="media-body">
                            <h3> 
                                <a href="/sblog?id=<?= $blogs[$i]['blog_id']; ?>">
                                    <?= $blogs[$i]['title']; ?>
                            </a>
                            </h3>
                                <a href="javascript:void(0);" 
                                onclick="var ok = confirm('削除しますか？'); if (ok) location.href='/dblog?id=<?= $blogs[$i]['blog_id']; ?>'">
                                <i class="far fa-trash-alt"></i> 削除</a>
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
            <!-- add article -->
            <div class="col-md-4 pt-4 pl-4">
                <button onclick="location.href='/mblog'" class="btn btn-primary">追加</button>
            </div>
        </div>
    </div>
</div>

<!-- show thumnail ver Enlarge -->
<div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <ol class="carousel-indicators">
            <?php for ($i = 0; $i < count($blogs); $i++): ?>
                <li data-target="#lightbox" data-slide-to="<?= $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
            <?php endfor; ?>
        </ol>

        <div class="carousel-inner">
            <?php for ($i = 0; $i < count($blogs); $i++): ?>
                <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>">
                <img src="image?id=<?= $blogs[$i]['thumnail_id']; ?>" class="d-block w-100">
                </div>
            <?php endfor; ?>
        </div>

        <a class="carousel-control-prev" href="#lightbox" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#lightbox" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>