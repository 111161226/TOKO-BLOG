@include('functions')
<?php

$pdo = connectDB();

$sql = 'DELETE FROM images WHERE image_id = :image_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':image_id', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();

header('Location:/home');
exit();
?>