@include('functions')
<?php

$pdo = connectDB();

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