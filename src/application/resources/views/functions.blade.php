<?php
    // connect database
    function connectDB() {
        // 環境変数から取得（設定されていなければ右側のデフォルト値を使用）
        $db_host = getenv('DB_HOST') ?: 'mysql'; // docker-composeのサービス名
        $db_name = getenv('DB_NAME') ?: 'mysql';
        $db_user = getenv('DB_USER') ?: 't1';
        $db_pass = getenv('DB_PASS') ?: 't2';
        $db_port = getenv('DB_PORT') ?: '3306';

        $param = "mysql:dbname={$db_name};host={$db_host};port={$db_port};charset=utf8mb4";
        
        try {
            $pdo = new PDO($param, $db_user, $db_pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, 
            ]);
            return $pdo;
        } catch (PDOException $e) {
            // 本番環境では詳細なエラーを出さないのがセキュリティ上の定石
            exit("DB Connection Error.");
        }
    }

    //get user name
    function getuserinfo($id) {
        $pdo = connectDB();
        //get user info
        try {
            $sql = "SELECT user_name, image_id FROM users INNER JOIN user_thumnail ON user_id = u_id  WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $userinfo = $stmt->fetch();
        } catch(Exception $error){
            echo "failed to get author info" . $error->getMessage();
            exit();
        }

        return $userinfo;
    }
?>