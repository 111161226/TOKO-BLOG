<?php
    // connect database
    function connectDB() {
        $param = 'mysql:dbname=mysql;host=mysql';
        try {
            $pdo = new PDO($param, "test", "test");
            return $pdo;

        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    //get user name
    function getname($id) {
        $pdo = connectDB();
        //get user info
        try {
            $sql = "SELECT * FROM users WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $userinfo = $stmt->fetch();
        } catch(Exception $error){
            echo "failed to login" . $error->getMessage();
            exit();
        }

        return $userinfo['user_name'];
    }
?>