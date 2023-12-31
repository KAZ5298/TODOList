<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Model/Users.php');
require_once('../App/Util/Safety.php');

$post = Safety::sanitize($_POST);

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

    $db = new Users();
    $users = $db->getAllUsers();
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
    <title>作業修正</title>
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
                    <a class="nav-link" href="./">作業一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./entry.php">作業登録 <span class="sr-only">(current)</span></a>
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
                作業内容を修正してください
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
                        <input type="hidden" name="item_id" id="item_id" class="form-control" value="<?= $_POST['item_id'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="item_name">項目名</label>
                        <input type="text" name="item_name" id="item_name" class="form-control" value="<?= $todoItem['item_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="user_id">担当者</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="" <?php if (!isset($todoItem['user_id'])) echo 'selected' ?>>--選択してください--</option>
                            <?php foreach ($users as $value) : ?>
                                <?php if ($value['is_deleted'] != 1) : ?>
                                    <option value="<?= $value['id'] ?>" <?php if (($value['id'] == $todoItem['user_id'])) echo 'selected' ?>><?= $value['family_name'] . $value['first_name'] ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expire_date">期限</label>
                        <input type="date" class="form-control" id="expire_date" name="expire_date" value="<?= $todoItem['expire_date'] ?>">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="finished" name="finished" value="<?php if (isset($todoItem['finished_date'])) echo $todoItem['finished_date'] ?>" <?php if (isset($todoItem['finished_date'])) echo 'checked' ?>>
                        <label for="finished">完了</label>
                    </div>
                    <button class="btn btn-primary" type="submit" name="action" value="edit">更新</button>
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