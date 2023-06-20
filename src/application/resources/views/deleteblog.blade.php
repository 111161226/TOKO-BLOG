@include('functions')
<?php

$pdo = connectDB();

//delete thumnail
try{
    $sql = 'DELETE images FROM images INNER JOIN blogs ON image_id = thumnail_id WHERE blog_id = :blog_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
} catch(Exception $error){
    echo "failed to delete thumnail" . $error->getMessage();
    exit();
}

//delete auther info of image
try {
    $sql = 'DELETE image_owner FROM image_owner INNER JOIN blogs ON album_id = thumnail_id WHERE blog_id = :blog_id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $stmt=null;
    echo "successed to delete the author info";
} catch (Exception $error) {
    echo "failed to delete the author info" . $error->getMessage();
    exit();
}

//delete blog
try{
    $sql = 'DELETE FROM blogs WHERE blog_id = :blog_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
} catch(Exception $error){
    echo "failed to delete blog" . $error->getMessage();
    exit();
}

header('Location:/lblog');
exit();
?>