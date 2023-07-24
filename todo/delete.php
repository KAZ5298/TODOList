<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Util/Safety.php');

unset($_SESSION['err_msg']);

$post = Safety::sanitize($_POST);

if (!isset($post['token']) || !Safety::isValidToken($post['token'])) {
    $_SESSION['err_msg'] = '不正な処理が行われました。';
    header('Location: ./index.php');
    exit;
}

if (empty($_SESSION['user'])) {
    header('Location: ../login/index.php');
    exit;
} else {
    $user = $_SESSION['user'];
}

$token = Safety::generateToken();

try {
    $db = new TodoItems();
    $todoItem = $db->selectByItemId($post['item_id']);
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>削除確認</title>
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
                    <a class="nav-link" href="./">作業一覧 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./entry.php">作業登録</a>
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
            <form class="form-inline my-2 my-lg-0" action="./" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">検索</button>
            </form>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-6 alert alert-info">
                下記の項目を削除します。よろしいですか？
            </div>
            <div class="col-sm-3"></div>
        </div>

        <!-- 入力フォーム -->
        <div class="row my-2">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <form action="./action.php" method="post">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <div class="form-group">
                        <input type="hidden" name="item_id" id="item_id" class="form-control" value="<?= $_POST['item_id'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="item_name">項目名</label>
                        <p name="item_name" id="item_name" class="form-control"><?= $todoItem['item_name'] ?></p>
                    </div>
                    <div class="form-group">
                        <label for="user_id">担当者</label>
                        <p name="user_id" id="user_id" class="form-control"><?= $todoItem['family_name'] . $todoItem['first_name'] ?></p>
                    </div>
                    <div class="form-group">
                        <label for="expire_date">期限</label>
                        <p class="form-control" id="expire_date" name="expire_date"><?= $todoItem['expire_date'] ?></p>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="finished" name="finished" <?php if (isset($todoItem['finished_date'])) {
                                                                                                            echo 'checked disabled';
                                                                                                        } else {
                                                                                                            echo 'disabled';
                                                                                                        } ?>>
                        <label for="finished">完了</label>
                    </div>
                    <button class="btn btn-danger" type="submit" name="action" value="delete">削除</button>
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