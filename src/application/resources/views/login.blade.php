<?php
    $err_msg = '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Login form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
        #btn {
            margin-left: 200px;
        }
        #lnk {
            margin-left: 120px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="bg-info d-flex align-items-center justify-content-center" height="auto">
        <form method="post">
        @csrf
        <h1>ログインページ</h1>
        <br>   
        <div>
            <label>
                ユーザー名：
                <input type="text" name="username" required>
            </label>
        </div>
        <div>
            <label>
                パスワード：
                <input type="password" name="pass" pattern="^[a-zA-Z0-9]+$" minlength="8" maxlength="30" required>
            </label>
        </div>
        <input type="submit" id="btn" class="btn btn-primary" value="ログイン">
        <?php if ($err_msg != ''): ?>
            <div class="invalid-feedback d-block"><?= $err_msg; ?></div>
        <?php endif; ?>
        <br>
        <p id="lnk">新規登録する方は<a href="/signup">こちら</a></p>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
