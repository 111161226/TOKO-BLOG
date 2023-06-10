@include('functions')
<?php

$pdo = connectDB();
$err_msg = '';

//get image from db
$sql = 'SELECT * FROM images ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$images = $stmt->fetchAll();

//post request process
/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //store images to db
    if (!empty($_FILES['image']['name'][0])) {
        $files = $_FILES['image'];

        for ($i =0; $i  < count($files['name']); $i++) { 
            $name = $files['name'][$i];
            $type = $files['type'][$i];
            $content = file_get_contents($files['tmp_name'][$i]);
            $size = $files['size'][$i];
            
            //check file type and size(2M)
            $maxFileSize = 1048576*2;
            $validFileTypes = ['image/png', 'image/jpeg'];
            if ($size > $maxFileSize || !in_array($type, $validFileTypes)) {
                $err_msg = 'please select * jpg, jpeg, png file up to 2MB';
            }

            //insert image into db
            if ($err_msg == '') {
                $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size, created_at)
                        VALUES (:image_name, :image_type, :image_content, :image_size, now())';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
                $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
                $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
                $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        header('Location:/');
        exit();
    } 
}*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Image Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 border-right">
            <!-- show image -->
            <ul class="list-unstyled">
                @csrf
                <?php for ($i = 0; $i < count($images); $i ++): ?>
                    <li class="media mt-5">
                        <a href="#lightbox" data-toggle="modal" data-slide-to="<?= $i; ?>">
                            <img src="image?id=<?= $images[$i]['image_id']; ?>" width="100" height="auto" class="mr-3">
                        </a>
                        <div class="media-body">
                        <h5><?= $images[$i]['image_name']; ?> (<?= number_format($images[$i]['image_size']/1000, 2); ?> KB)</h5>
                            <a href="javascript:void(0);" 
                               onclick="var ok = confirm('削除しますか？'); if (ok) location.href='/remove?id=<?= $images[$i]['image_id']; ?>'">
                              <i class="far fa-trash-alt"></i> 削除</a>
                        </div>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
        <!-- store image -->
        <div class="col-md-4 pt-4 pl-4">
            <form method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>画像を選択</label>
                    <input type="file" name="image[]" multiple="multiple" accept=".jpg,.jpeg,.png" required>
                    <?php if ($err_msg != ''): ?>
                        <div class="invalid-feedback d-block"><?= $err_msg; ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">保存</button>
            </form>
        </div>
    </div>
</div>

<!-- show image ver Enlarge -->
<div class="modal carousel slide" id="lightbox" tabindex="-1" role="dialog" data-ride="carousel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <ol class="carousel-indicators">
            <?php for ($i = 0; $i < count($images); $i++): ?>
                <li data-target="#lightbox" data-slide-to="<?= $i; ?>" <?php if ($i == 0) echo 'class="active"'; ?>></li>
            <?php endfor; ?>
        </ol>

        <div class="carousel-inner">
            <?php for ($i = 0; $i < count($images); $i++): ?>
                <div class="carousel-item <?php if ($i == 0) echo 'active'; ?>">
                <img src="image.php?id=<?= $images[$i]['image_id']; ?>" class="d-block w-100">
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