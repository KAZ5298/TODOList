<?php

require_once('../App/Model/Base.php');
require_once('../App/Util/Safety.php');

$token = Safety::generateToken();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>ログイン</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <span class="navbar-brand">TODOリスト</span>
    </nav>

    <div class="container">
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-3">
                <h1>ログイン</h1>
            </div>
            <div class="col-sm-3"></div>
        </div>

        <?php if (isset($_SESSION['err_msg'])) : ?>
            <div class="row my-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                    <?= $_SESSION['err_msg'] ?> <button class="close" data-dismiss="alert">&times;</button>
                </div>
                <div class="col-sm-3"></div>
            </div>
        <?php endif ?>
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form action="./login.php" method="post">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <div class="form-group">
                        <label for="user">ユーザー名</label>
                        <input type="text" class="form-control" id="user" name="user" value="<?php isset($_SESSION['user']) ? $_SESSION['user'] : '' ?>">
                    </div>
                    <div class=" form-group">
                        <label for="password">パスワード</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary">ログイン</button>
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>

    <!-- 必要なJavascriptを読み込む -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>