@include('functions')
<?php

//check post parameter 
if (isset($_POST['tid']) && isset ($_POST['username']) && isset($_POST['pass'])) {
    //assign data from form
    $name = $_POST['username'];
    $tid = $_POST['tid'];
    $curpass = $_POST['pass'];
    $pdo = connectDB();

    try {
        $sql = "SELECT * FROM users WHERE user_id = :u_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':u_id', $_SESSION['id']);
        $stmt->execute();
        $member = $stmt->fetch();
    } catch(Exception $error){
        echo "failed to update" . $error->getMessage();
        exit();
    }
    //check the password is correct
    if (!$member || !password_verify($curpass, $member['password'])) {
        $err_msg = 'You made mistakes on password';
        echo($err_msg);
        var_dump($member);
        var_dump($name);
        var_dump($curpass);
        exit();
    }

    //check update is needed
    if ($_POST['newpass'] != '') {
        $stmt = null;
        //check new username has already been used
        if ($_SESSION['name'] != $name) {
            try {
                $sql = "SELECT * FROM users WHERE user_name = :name";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':name', $name, PDO::PARAM_STR);
                $stmt->execute();
                $mb = $stmt->fetch();
            } catch(Exception $error){
                echo "failed to update" . $error->getMessage();
                exit();
            }
            if ($mb['user_name'] == $name) {
                $err_msg = 'username is already taken';
                echo($err_msg);
                exit();
            }
        }
        $stmt = null;
        $pass = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
        //check user can update
        try {
            $sql = "UPDATE users SET user_name = :name, password = :pass WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->execute();
        } catch(Exception $error){
            echo "failed to update" . $error->getMessage();
            exit();
        }
        $_SESSION['name'] = $name;
    } elseif ($name != $_SESSION['name']) {
        $stmt = null;
        //check new username has already been used
        try {
            $sql = "SELECT * FROM users WHERE user_name = :name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name);
            $stmt->execute();
            $mb = $stmt->fetch();
        } catch(Exception $error){
            echo "failed to update" . $error->getMessage();
            exit();
        }
        if ($mb['user_name'] == $name) {
            $err_msg = 'username is already taken';
            echo($err_msg);
            exit();
        }
        //check user can update
        try {
            $stmt = null;
            $sql = "UPDATE users SET `user_name` = :name WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->execute();
        } catch(Exception $error){
            echo "failed to update" . $error->getMessage();
            exit();
        }
        $_SESSION['name'] = $name;
    }

    //check thumnail is needed to update
    if(!empty($_FILES['thumnail']['name'])) {
        $err_msg = '';
        $thumnail = $_FILES['thumnail'];
        //check image info
        $type = $thumnail['type'];
        $image_content = file_get_contents($thumnail['tmp_name']);
        $size = $thumnail['size'];
        $t_name = $thumnail['name'];

        //check file type and size(2M)
        $maxFileSize = 1048576*2;
        $validFileTypes = ['image/png', 'image/jpeg'];
        if ($size > $maxFileSize || !in_array($type, $validFileTypes)) {
            $err_msg = 'please select * jpg, jpeg, png file up to 2MB';
            echo $err_msg;
            exit();
        }

        //update thumnail into db
        if ($err_msg == '') {
            //check error
            try {
                $stmt = null;
                $sql = 'UPDATE user_thumnail SET image_name = :image_name, image_type = :image_type, image_content = :image_content, 
                        image_size = :image_size, updated_at = now() WHERE u_id = :user_id AND image_id = :image_id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $_SESSION['id'], PDO::PARAM_STR);
                $stmt->bindValue(':image_id', $tid, PDO::PARAM_STR);
                $stmt->bindValue(':image_name', $t_name, PDO::PARAM_STR);
                $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
                $stmt->bindValue(':image_content', $image_content, PDO::PARAM_STR);
                $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
                $stmt->execute();
            } catch(Exception $error){
                echo "failed to upload thumnail" . $error->getMessage();
                exit();
            }
        }
    }

    header('Location: /profile');
    echo "success to update";
    exit();
}
?>