@include('functions')
<?php
//assign data from form
$name = $_POST['username'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

$pdo = connectDB();

//check username has already been used
$sql = "SELECT * FROM users WHERE user_name = :name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name);
$stmt->execute();
$member = $stmt->fetch();
if ($member['user_name'] === $name) {
    $msg = 'username is already taken';
    $err_msg = $msg;
    $link = 'Location:/signup';
} else {
    //if not registered same user_name, insert new user data 
    $sql = "INSERT INTO users(user_id, user_name, password) VALUES (:userId, :name, :pass)";
    $stmt = $pdo->prepare($sql);
    $userId = uniqid('',true);
    $stmt->bindValue(':userId', $userId);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':pass', $pass);
    $stmt->execute();
    $msg = 'finish registering';
    $link = 'Location:/login';
}

header($link);
echo $msg;
exit();
?>