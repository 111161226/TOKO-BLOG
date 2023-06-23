@include('functions')
<?php
//assign data from form
$name = $_POST['username'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$userId = uniqid('',true);

$pdo = connectDB();

$thumnail = $_FILES['thumnail'];
$t_name = $thumnail['name'];
$type = $thumnail['type'];
$content = file_get_contents($thumnail['tmp_name']);
$size = $thumnail['size'];
$err_msg = '';

//check file type and size(2M)
$maxFileSize = 1048576*2;
$validFileTypes = ['image/png', 'image/jpeg'];
if ($size > $maxFileSize || !in_array($type, $validFileTypes)) {
    $err_msg = 'please select * jpg, jpeg, png file up to 2MB';
    echo $err_msg;
    exit();
}

//insert thumnail into db
if ($err_msg == '') {
    //check error
    try {
        $sql = 'INSERT INTO user_thumnail (u_id, image_id, image_name, image_type, image_content, image_size, updated_at)
                VALUES (:user_id, :image_id, :image_name, :image_type, :image_content, :image_size, now())';
        $stmt = $pdo->prepare($sql);
        $tid = uniqid('',true);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_STR);
        $stmt->bindValue(':image_id', $tid, PDO::PARAM_STR);
        $stmt->bindValue(':image_name', $t_name, PDO::PARAM_STR);
        $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
        $stmt->execute();
    } catch(Exception $error){
        echo "failed to upload thumnail" . $error->getMessage();
        exit();
    }
}

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