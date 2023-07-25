<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/Users.php');
require_once('../App/Util/Safety.php');

if (empty($_SESSION['user'])) {
    header('Location: ../login/index.php');
    exit;
} else {
    $user = $_SESSION['user'];
}

$token = Safety::generateToken();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>ユーザー情報登録</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>

<body>
    <!-- ナビゲーション -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <span class="navbar-brand">TODOリスト</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../todo/">作業一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./">ユーザー一覧 </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./entry.php">ユーザー登録<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $user['family_name'] . $user['first_name'] . 'さん' ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../login/logout.php">ログアウト</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-6 alert alert-info">
                ユーザー情報を登録してください
            </div>
            <div class="col-sm-3"></div>
        </div>

        <!-- エラーメッセージ -->
        <?php if (isset($_SESSION['err_msg'])) : ?>
            <div class="row my-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                    <?= $_SESSION['err_msg'] ?> <button class="close" data-dismiss="alert">&times;</button>
                </div>
                <div class="col-sm-3"></div>
            </div>
        <?php endif ?>
        <!-- エラーメッセージ ここまで -->

        <!-- 入力フォーム -->
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form action="./action.php" method="post">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <div class="form-group">
                        <label for="login_user">ログインユーザー名</label>
                        <input type="text" name="login_user" id="login_user" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="pass">パスワード</label>
                        <input type="text" name="pass" id="pass" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="family_name">ユーザー姓</label>
                        <input type="text" name="family_name" id="family_name" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="first_name">ユーザー名</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label for="is_admin">管理者権限</label><br>
                        <input type="radio" name="is_admin" id="is_admin" class="form-check-input-inline" value="1">
                        <label class="is_admin-inline" for="is_admin">あり</label>
                        <input type="radio" name="is_admin" id="is_admin" class="form-check-input-inline" value="0">
                        <label class="is_admin-inline" for="is_admin">なし</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_deleted" name="is_deleted" value="1">
                        <label for="is_deleted">削除フラグ</label>
                    </div>
                    <button class="btn btn-primary" type="submit" name="action" value="entry">登録</button>
                    <input type="button" value="キャンセル" class="btn btn-outline-primary" onclick="location.href='./';">
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <!-- 入力フォーム ここまで -->

    </div>
    <!-- コンテナ ここまで -->

    <!-- 必要なJavascriptを読み込む -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>