<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Util/Common.php');
require_once('../App/Util/Safety.php');

unset($_SESSION['post']);
unset($_SESSION['err_msg']);

if (empty($_SESSION['user'])) {
    header('Location: ../login/index.php');
    exit;
} else {
    $user = $_SESSION['user'];
}

if ($user['is_admin'] != 1) {
    $_SESSION['err_msg'] = '管理者権限がありません';
    header('Location: ../login/index.php');
    exit;
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
    <title>担当者一覧</title>
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
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./">ユーザー一覧 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./entry.php">ユーザー登録</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $user['family_name'] . $user['first_name'] . 'さん' ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../management/index.php">ユーザー管理</a>
                        <a class="dropdown-item" href="../login/logout.php">ログアウト</a>
                    </div>
                </li>
            </ul>
            <!-- <form class="form-inline my-2 my-lg-0" action="./" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">検索</button>
            </form> -->
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">

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
                <?php foreach ($rec as $value) : ?>
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
                            <?= $value['family_name'] . $value['first_name'] ?> </td>
                        <td class="align-middle">
                            <?= $value['registration_date'] ?> </td>
                        <td class="align-middle">
                            <?= $value['expire_date'] ?> </td>
                        <td class="align-middle">
                            <?php if (isset($value['finished_date'])) {
                                echo $value['finished_date'];
                            } else {
                                echo '未';
                            }
                            ?>
                        <td class="align-middle button">
                            <form action="./complete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <button class="btn btn-primary my-0" type="submit" name="action" value="complete">完了</button>
                            </form>
                            <form action="./edit.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="修正">
                            </form>
                            <form action="./delete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="item_id" value="<?= $value['todo_id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="削除">
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        </div>

    </div>
    <!-- コンテナ ここまで -->

    <!-- 必要なJavascriptを読み込む -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>