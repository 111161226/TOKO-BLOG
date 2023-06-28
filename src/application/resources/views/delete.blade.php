@include('functions')
<?php

$pdo = connectDB();

//delete auther info of image
try {
    $sql = 'DELETE image_owner WHERE album_id = :image_id AND author_id = :uid';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->bindValue(':uid', $_SESSION['id'], PDO::PARAM_STR);
    $stmt->execute();
    $stmt=null;
    echo "successed to delete the author info";
} catch (Exception $error) {
    echo "failed to delete the author info" . $error->getMessage();
    exit();
}

try{
    $sql = 'DELETE FROM images WHERE image_id = :image_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
} catch(Exception $error){
    echo "failed to get images" . $error->getMessage();
    exit();
}
header('Location:/home');
exit();
?>