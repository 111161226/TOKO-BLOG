@include('functions')
<?php
//check post parameter 
if (isset ($_POST['title']) && isset ($_POST['content']) && isset ($_POST['category']) && isset ($_FILES['thumnail'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $thumnail = $_FILES['thumnail'];

    //check image
    $type = $thumnail['type'];
    $image_content = file_get_contents($thumnail['tmp_name']);
    $size = $thumnail['size'];
    $name = $thumnail['name'];

    //check file type and size(2M)
    $maxFileSize = 1048576*2;
    $validFileTypes = ['image/png', 'image/jpeg'];
    $err_msg = '';
    if ($size > $maxFileSize || !in_array($type, $validFileTypes)) {
        $err_msg = 'please select * jpg, jpeg, png file up to 2MB';
    }
    if ($err_msg != ''){
        echo $err_msg;
        exit();
    }

    //connect to mysql
    $pdo = connectDB();

    //check the category is new
    try {
        $que = "SELECT COUNT(*) FROM `category_list` WHERE category = :category";
        $stmt = $pdo->prepare($que);
        $stmt->bindValue(':category', $category);
        $stmt->execute();
        $res = $stmt->fetch();
        $stmt = null;
        if (count($res) == 0) {
            $que = "INSERT INTO category_list (category) VALUES (:category)";
            $stmt = $pdo->prepare($que);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;
            echo "カテゴリーを追加しました。";
        }
    }
    catch (Exception $error) {
        echo "カテゴリーの登録失敗：" . $error->getMessage();
        exit();
    }

    //add blog to db
    try {
        $que = "INSERT INTO `blogs` (`blog_id`, `title`, `content`, `created_at`, `thumnail_id`, `c_id`) 
                VALUES (:bid, :title, :content, now(), :tid, (SELECT `c_id` FROM `category_list` WHERE `category` = :category))";
        $stmt = $pdo->prepare($que);
        $bid = uniqid('',true);
        $tid = uniqid('',true);
        $stmt->bindValue(':bid', $bid, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':tid', $tid, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = null;
        echo "記事を追加しました。";
    }
    catch (Exception $error) {
        echo "記事の登録失敗：" . $error->getMessage();
        exit();
    }

    //add thumnail image into db
    try {
        $sql = 'INSERT INTO images(image_id, image_name, image_type, image_content, image_size, created_at)
                            VALUES (:image_id, :image_name, :image_type, :image_content, :image_size, now())';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':image_id', $tid, PDO::PARAM_STR);
        $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':image_content', $image_content, PDO::PARAM_STR);
        $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
        $stmt->execute();
        $stmt=null;
        echo "successed to store the thumnail";
    }
    catch (Exception $error) {
        echo "failed to store the thumnail" . $error->getMessage();
        exit();
    }

    header('Location:/lblog');
    exit();
}
?>