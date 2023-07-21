<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Util/Common.php');
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

try {
    if (empty($post['user_id'])) {
        $_SESSION['err_msg'] = '担当者を選択してください。';
        header('Location: ./entry.php');
        exit;
    }

    if (strlen($post['item_name']) > 100) {
        $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
        header('Location: ./entry.php');
        exit;
    }

    $db = new Common();
    $dt = $db->getDate();

    if (isset($post['finished'])) {
        $finished_date = $dt;
    } else {
        $finished_date = "";
    }

    $db = new TodoItems();
    $db->insertTodoItem($post['user_id'], $post['item_name'], $dt, $post['expire_date'], $finished_date);

    header('Location: ./index.php');
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
