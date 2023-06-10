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
?>