@include('functions')
<?php
//check post parameter 
if (isset($_POST['id']) && isset ($_POST['title']) && isset ($_POST['content']) && isset ($_POST['category']) && isset($_POST['tid'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $id = $_POST['id'];
    $tid = $_POST['tid'];
    
    //connect to mysql
    $pdo = connectDB();

    //check the category is new
    try {
        $que = "SELECT * FROM `category_list` WHERE category = :category";
        $stmt = $pdo->prepare($que);
        $stmt->bindValue(':category', $category);
        $stmt->execute();
        $res = $stmt->fetch(); 
    }
    catch (Exception $error) {
        echo "failed to register new category" . $error->getMessage();
        exit();
    }

    //get category id
    if (empty($res)) {
        try {
            $stmt = null;
            $que = "INSERT INTO category_list (category) VALUES (:category)";
            $stmt = $pdo->prepare($que);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            $stmt = null;
            echo "カテゴリーを追加しました。";
            $cid = $pdo->lastInsertId();
        } catch (Exception $error) {
            echo "can't get category id" . $error->getMessage();
            exit();
        }
    } else {
        $cid = $res['c_id'];
    }

    //update blog to db
    try {
        $que = "UPDATE blogs SET  `title` = :title, `content` = :content, c_id = :cid WHERE blog_id =:id";
        $stmt = $pdo->prepare($que);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':cid', $cid, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = null;
        echo "succeed update blog";
    }
    catch (Exception $error) {
        echo "failed to update blog" . $error->getMessage();
        exit();
    }

    //update thumnail image
    if (!empty($_FILES['thumnail']['name'])) {
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

        try {
            $sql = 'UPDATE images SET image_name =:image_name, image_type =:image_type, image_content =:image_content, 
                            image_size =:image_size, created_at = now() WHERE image_id =:tid';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':image_content', $image_content, PDO::PARAM_STR);
            $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
            $stmt->bindValue(':tid', $tid, PDO::PARAM_STR);
            $stmt->execute();
            $stmt=null;
            echo "successed to update the thumnail";
        }
        catch (Exception $error) {
            echo "failed to update the thumnail" . $error->getMessage();
            exit();
        }
    }
    $link = 'Location:/sblog?id=' . $id;
    header($link);
    exit();
}
?>