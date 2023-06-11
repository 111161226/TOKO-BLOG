@include('functions')
<?php
//assign data from form
$name = $_POST['username'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

$pdo = connectDB();

//check username has already been used
try {
    $sql = "SELECT * FROM users WHERE user_name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->execute();
    $member = $stmt->fetch();
} catch(Exception $error){
    echo "failed to register" . $error->getMessage();
    exit();
}

if ($member['user_name'] === $name) {
    $err_msg = 'username is already taken';
    echo($err_msg);
    exit();
} //if not registered same user_name, insert new user data
else {
    //check error
    try { 
        $sql = "INSERT INTO users(user_id, user_name, password) VALUES (:userId, :name, :pass)";
        $stmt = $pdo->prepare($sql);
        $userId = uniqid('',true);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':pass', $pass);
        $stmt->execute();
        $msg = 'finish registering';
        $link = 'Location:/login';
    } catch(Exception $error){
        echo "failed to register" . $error->getMessage();
        exit();
    }
}

header($link);
echo $msg;
exit();
?>