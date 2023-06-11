@include('functions')
<?php
session_start();
//assign data from form
$name = $_POST['username'];
$pass = $_POST['pass'];

$pdo = connectDB();

//check user can login
$sql = "SELECT * FROM users WHERE user_name = :name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name);
$stmt->execute();
$member = $stmt->fetch();

//check the password is correct
if (password_verify($pass, $member['password'])) {
    $_SESSION['id'] = $member['user_id'];
    $_SESSION['name'] = $member['user_name'];
    $msg = 'You success login';
    $link = 'Location:/home';
} else {
    $err_msg = 'You made mistakes on username or password';
    echo($err_msg);
    exit();
}
header($link);
echo($msg);
exit();
?>