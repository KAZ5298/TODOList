<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Util/Common.php');
require_once('../App/Util/Safety.php');

unset($_SESSION['post']);
unset($_SESSION['err_msg']);

$isGetFlg = 0;

if (empty($_SESSION['user'])) {
    header('Location: ../login/index.php');
    exit;
} else {
    $user = $_SESSION['user'];
}

$token = Safety::generateToken();

try {
    $db = new Common();
    $dt = $db->getDate();

    if (isset($_GET['search'])) {
        $get = Safety::sanitize($_GET);
        $db = new TodoItems();
        $todoItems = $db->searchItems($get['search']);
        $isGetFlg = 1;
    } else {
        $db = new TodoItems();
        $todoItems = $db->getAllItems();
    }

    // ページャー機能
    // １ページ当たりの表示数
    define('MAX', '5');

    //データ総数
    $todoItems_num = count($todoItems);

    // トータルページ数
    $max_page = ceil($todoItems_num / MAX);

    // 現在ページ番号
    if (!isset($_GET['page_id'])) {
        $now_page = 1;
    } else {
        $now_page = $_GET['page_id'];
    }

    // ページ番号
    if ($now_page == 1 || $now_page == $max_page) {
        $range = 4;
    } elseif ($now_page == 2 || $now_page == $max_page - 1) {
        $range = 3;
    } else {
        $range = 2;
    }

    // 件数表示用
    $from_record = ($now_page - 1) * MAX + 1;
    if ($now_page == $max_page && $todoItems_num % MAX !== 0) {
        $to_record = ($now_page - 1) * MAX + $todoItems_num % MAX;
    } else {
        $to_record = $now_page * MAX;
    }

    // 配列の取得位置
    $start_no = ($now_page - 1) * MAX;

    $rec = array_slice($todoItems, $start_no, MAX, true);
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
    <title>作業一覧</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        /* ボタンを横並びにする */
        form {
            display: inline-block;
        }

        /* 打消し線を入れる */
        tr.del>td {
            text-decoration: line-through;
        }

        /* ボタンのセルは打消し線を入れない */
        tr.del>td.button {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- ナビゲーション -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <span class="navbar-brand">TODOリスト</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./">作業一覧 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./entry.php">作業登録</a>
                </li>
                <?php if ($user['is_admin'] != 0): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../management/index.php">ユーザー管理</a>
                    </li>
                <?php endif ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $user['family_name'] . $user['first_name'] . 'さん' ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../login/logout.php">ログアウト</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="./" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search"
                    value="">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">検索</button>
            </form>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">

        <?php if ($isGetFlg == 1): ?>
            <label>検索対象：【項目名】【担当者】　検索内容：
                <?= $_GET['search'] ?>
            </label>
        <?php endif ?>

        <table class="table table-striped table-hover table-sm my-2">
            <thead>
                <tr>
                    <th scope="col">項目名</th>
                    <th scope="col">担当者</th>
                    <th scope="col">登録日</th>
                    <th scope="col">期限日</th>
                    <th scope="col">完了日</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($rec as $value): ?>
                    <?php if (isset($value['finished_date'])) {
                        $class = 'del';
                    } elseif ($dt > $value['expire_date']) {
                        $class = 'text-danger';
                    } else {
                        $class = '';
                    }
                    ?>
                    <tr class="<?= $class ?>">
                        <td class="align-middle">
                            <?= $value['item_name'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $value['family_name'] . $value['first_name'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $value['registration_date'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $value['expire_date'] ?>
                        </td>
                        <td class="align-middle">
                            <?php if (isset($value['finished_date'])): ?>
                                <?= $value['finished_date'] ?>
                            <?php else: ?>
                                未
                            <?php endif ?>
                        <td class="align-middle button">
                            <form action="./complete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <button class="btn btn-primary my-0" type="submit" name="action"
                                    value="complete">完了</button>
                            </form>
                            <form action="./edit.php" method="post" class="my-sm-1">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="修正">
                            </form>
                            <form action="./delete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="削除">
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <!-- 件数表示 -->
        <p class="from_to">
            <?= $todoItems_num ?> 件中
            <?= $from_record ?> -
            <?= $to_record ?> 件目を表示
        </p>

        <div class="pagination">
            <!-- 戻る -->
            <?php if ($now_page >= 2): ?>
                <a href="./?page_id=<?= $now_page - 1 ?>" class="page_feed">&laquo;</a>
            <?php else: ?>
                <span class="first_last_page">&laquo;</span>
            <?php endif ?>

            <!-- ページ表示 -->
            <?php for ($i = 1; $i <= $max_page; $i++): ?>
                <?php if ($i >= $now_page - $range && $i <= $now_page + $range): ?>
                    <?php if ($i == $now_page): ?>
                        <span class="now_page_number">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="./?page_id=<?= $i ?>" class="page_number"><?= $i ?></a>
                    <?php endif ?>
                <?php endif ?>
            <?php endfor ?>

            <!-- 進む -->
            <?php if ($now_page < $max_page): ?>
                <a href="./?page_id=<?= $now_page + 1 ?>" class="page_feed">&raquo;</a>
            <?php else: ?>
                <span class="first_last_page">&raquo;</span>
            <?php endif ?>

        </div>

        <!-- 検索のとき、戻るボタンを表示する -->
        <?php if ($isGetFlg == 1): ?>
            <div class="row">
                <div class="col">
                    <form>
                        <div class="goback">
                            <input class="btn btn-primary my-0" type="button" value="戻る" onclick="location.href='./';">
                        </div>
                    </form>
                </div>
            </div>
        <?php endif ?>

    </div>
    <!-- コンテナ ここまで -->

    <!-- 必要なJavascriptを読み込む -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>