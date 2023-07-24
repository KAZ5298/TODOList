<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/Users.php');
require_once('../App/Util/Common.php');
require_once('../App/Util/Safety.php');

unset($_SESSION['post']);
unset($_SESSION['err_msg']);

if (empty($_SESSION['user'])) {
    header('Location: ../login/');
    exit;
} else {
    $user = $_SESSION['user'];
}

if ($user['is_admin'] != 1) {
    $_SESSION['err_msg'] = '管理者権限がありません';
    unset($_SESSION['user']);
    unset($_SESSION['post']);
    header('Location: ../login/');
    exit;
}

$token = Safety::generateToken();

try {
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
                    <a class="nav-link" href="../todo/">作業一覧</a>
                </li>
                <li class="nav-item">
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
                        <a class="dropdown-item" href="../login/logout.php">ログアウト</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">

        <table class="table table-striped table-hover table-sm my-2">
            <thead>
                <tr>
                    <th scope="col">ログインユーザー名</th>
                    <th scope="col">ユーザー姓</th>
                    <th scope="col">ユーザー名</th>
                    <th scope="col">管理者権限</th>
                    <th scope="col">削除フラグ</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $value) : ?>
                    <tr>
                        <td class="align-middle">
                            <?= $value['user'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $value['family_name'] ?> </td>
                        <td class="align-middle">
                            <?= $value['first_name'] ?> </td>
                        <td class="align-middle">
                            <?= $value['is_admin'] ?> </td>
                        <td class="align-middle">
                            <?= $value['is_deleted'] ?> </td>
                        <td class="align-middle button">
                            <form action="./edit.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="user_id" value="<?= $value['id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="修正">
                            </form>
                            <form action="./delete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="token" value="<?= $token ?>">
                                <input type="hidden" name="user_id" value="<?= $value['id'] ?>">
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