<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/TodoItems.php');
require_once('../App/Util/Common.php');
require_once('../App/Util/Safety.php');
require_once('../App/Util/Validation.php');

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

$url = $_SERVER['HTTP_REFERER'];

try {
    // 現在日付取得
    $db = new Common();
    $dt = $db->getDate();

    // バリデーションチェック

    if (!Validation::userNullCheck($post['user_id'])) {
        $_SESSION['err_msg'] = '担当者を選択してください。';
        header("Location: ${url}", true, 307);
        exit;
    }

    if (!Validation::itemNullCheck($post['item_name'])) {
        $_SESSION['err_msg'] = '項目名が空白です。';
        header("Location: ${url}", true, 307);
        exit;
    }

    if (!Validation::strLenChk($post['item_name'])) {
        $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
        header("Location: ${url}", true, 307);
        exit;
    }

    // 登録・修正の共通処理
    if (isset($post['finished'])) {
        $finished_date = $dt;
    } else {
        $finished_date = "";
    }


    // 登録・修正・完了・削除
    switch ($post['action']) {
        // 登録
        case 'entry':
            $db = new TodoItems();
            $db->insertTodoItem($post['user_id'], $post['item_name'], $dt, $post['expire_date'], $finished_date);

            header('Location: ./index.php');
            exit;
        // 修正
        case 'edit':
            $db = new TodoItems();
            $db->editTodoItem($post['item_id'], $post['user_id'], $post['item_name'], $post['expire_date'], $finished_date);

            header('Location: ./index.php');
            exit;
        // 削除
        case 'delete':
            $db = new TodoItems();
            $db->deleteTodoItem($post['item_id']);

            header('Location: ./index.php');
            exit;
        // 完了
        case 'complete':
            $db = new TodoItems();
            $db->todoItemIsComplete($dt, $post['item_id']);

            header('Location: ./index.php');
            exit;

        default:
            header('Location: ./index.php');
            exit;
    }
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}