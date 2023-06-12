@include('functions')
<?php
$pdo = connectDB();

//check error
try{
    $sql = 'SELECT * FROM images WHERE image_id = :image_id LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image_id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $image = $stmt->fetch();
} catch(Exception $error){
    echo "failed to show image" . $error->getMessage();
    exit();
}

header('Content-type: ' . $image['image_type']);
echo $image['image_content'];
exit();
?>