@include('functions')
<?php
//check post parameter 
if (isset ($_POST['title']) && isset ($_POST['text']) && isset ($_POST['category']) && isset ($_FILES['thumnail'])) {
    $title = $_POST['title'];
    $text = $_POST['text'];
    $category = $_POST['category'];
    $thumnail = $_FILES['thumnail'];

    //check image
    $type = $thumnail['type'];
    $content = file_get_contents($thumnail['tmp_name']);
    $size = $thumnail['size'];

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
        $que = "SELECT COUNT(*) FROM category_list WHERE category = :category";
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
        $que = "INSERT INTO blog (blog_id, title, text, date, thumnail, category_id) VALUES (:bid, :title, :text, NOW(), :image, (SELECT id FROM category_list WHERE category = :category))";
        $stmt = $pdo->prepare($que);
        $bid = uniqid('',true);
        $stmt->bindValue(':bid', $bid, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':text', $text, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':image', $content, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = null;
        echo "記事を追加しました。";
    }
    catch (Exception $error) {
        echo "記事の登録失敗：" . $error->getMessage();
        exit();
    }
    header('Location:/home');
    exit();
}
?>