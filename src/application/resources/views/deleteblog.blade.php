@include('functions')
<?php

$pdo = connectDB();

$sql = 'DELETE FROM blogs WHERE blog_id = :blog_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':blog_id', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();

header('Location:/lblog');
exit();
?>