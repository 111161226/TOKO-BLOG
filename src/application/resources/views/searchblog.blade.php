@include('functions')
<?php
    $pdo = connectDB();
    $blogs = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(!($_POST["title"] == '' && $_POST["category"] == '')){
            //get all blogs from db
            try {
                $sql = "SELECT blog_id, title, content, category, thumnail_id FROM `blogs` INNER JOIN `category_list` ON blogs.`c_id` = `category_list`.`c_id`  
                        WHERE title LIKE '%".$_POST["title"]."%' AND category LIKE '%".$_POST["category"]."%' ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $blogs = $stmt->fetchAll();
            } catch (Exception $error) {
                echo "can't get blog" . $error->getMessage();
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Search form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="sidebar">
        @include('sidebar')  
    </div>
    <div class="body">
        <form method="post">
            @csrf
            <p class="text-center">
            <br>
            <label>タイトル&nbsp;</label> <input type="text" name="title">
            <label>&nbsp;カテゴリー&nbsp;</label> <input type="text" name="category"> &nbsp; 
            <input type="submit" name="submit" value="送信">
            </p>
        </form>
        <div class="col-md-8 border-right">
                <!-- show blog -->
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
                            </div>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>