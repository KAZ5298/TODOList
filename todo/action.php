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

try {
    // 現在日付取得
    $db = new Common();
    $dt = $db->getDate();

    // バリデーションチェック

    if (empty($post['user_id'])) {
        $_SESSION['err_msg'] = '担当者を選択してください。';
        if ($post['action'] == 'entry') {
            header('Location: ./entry.php');
            exit;
        } elseif ($post['action'] == 'edit') {
            header('Location: ./edit.php');
            exit;
        }
    }

    // if (!Validation::userNullCheck($post['user_id'])) {
    //     $_SESSION['err_msg'] = '担当者を選択してください。';
    //     header('Location: ./entry.php');
    //     exit;
    // }

    // if (!Validation::itemNullCheck($post['item_name'])) {
    //     $_SESSION['err_msg'] = '項目名が空白です。';
    //     if ($post['action'] == 'entry') {
    //         header('Location: ./entry.php');
    //         exit;
    //     } elseif ($post['action'] == 'edit') {
    //         header('Location: ./edit.php');
    //         exit;
    //     }
    // }

    // if (!Validation::lengthCheck($post['item_name'])) {
    //     $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
    //     if ($post['action'] == 'entry') {
    //         header('Location: ./entry.php');
    //         exit;
    //     } elseif ($post['action'] == 'edit') {
    //         header('Location: ./edit.php');
    //         exit;
    //     }
    // }

    if (empty($post['item_name'])) {
        $_SESSION['err_msg'] = '項目名が空白です。';
        if ($post['action'] == 'entry') {
            header('Location: ./entry.php');
            exit;
        } elseif ($post['action'] == 'edit') {
            header('Location: ./edit.php');
            exit;
        }
    }

    if (strlen($post['item_name']) > 100) {
        $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
        if ($post['action'] == 'entry') {
            header('Location: ./entry.php');
            exit;
        } elseif ($post['action'] == 'edit') {
            header('Location: ./edit.php');
            exit;
        }
    }

    // 登録・修正・完了・削除
    if ($post['action'] == 'entry') {
        if (isset($post['finished'])) {
            $finished_date = $dt;
        } else {
            $finished_date = "";
        }

        $db = new TodoItems();
        $db->insertTodoItem($post['user_id'], $post['item_name'], $dt, $post['expire_date'], $finished_date);
        $_SESSION['postkakunin'] = $post;

        header('Location: ./index.php');
        exit;
    } elseif ($post['action'] == 'edit') {
        if (isset($post['finished'])) {
            $finished_date = $dt;
        } else {
            $finished_date = "";
        }

        $db = new TodoItems();
        $db->editTodoItem($post['item_id'], $post['user_id'], $post['item_name'], $post['expire_date'], $finished_date);
        $_SESSION['postkakunin'] = $post;
        header('Location: ./index.php');
        exit;
    } elseif ($post['action'] == 'complete') {
        $db = new TodoItems();
        $db->todoItemIsComplete($dt, $post['item_id']);

        header('Location: ./index.php');
        exit;
    } elseif ($post['action'] == 'delete') {
        $db = new TodoItems();
        $db->deleteTodoItem($post['item_id']);

        header('Location: ./index.php');
        exit;
    }

    header('Location: ./index.php');
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
