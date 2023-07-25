<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/Users.php');
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
    // バリデーションチェック

    // if (empty($post['user_id'])) {
    //     $_SESSION['err_msg'] = '担当者を選択してください。';
    //     if ($post['action'] == 'entry') {
    //         header('Location: ./entry.php');
    //         exit;
    //     } elseif ($post['action'] == 'edit') {
    //         header('Location: ./edit.php');
    //         exit;
    //     }
    // }

    // // if (!Validation::userNullCheck($post['user_id'])) {
    // //     $_SESSION['err_msg'] = '担当者を選択してください。';
    // //     header('Location: ./entry.php');
    // //     exit;
    // // }

    // // if (!Validation::itemNullCheck($post['item_name'])) {
    // //     $_SESSION['err_msg'] = '項目名が空白です。';
    // //     if ($post['action'] == 'entry') {
    // //         header('Location: ./entry.php');
    // //         exit;
    // //     } elseif ($post['action'] == 'edit') {
    // //         header('Location: ./edit.php');
    // //         exit;
    // //     }
    // // }

    // // if (!Validation::lengthCheck($post['item_name'])) {
    // //     $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
    // //     if ($post['action'] == 'entry') {
    // //         header('Location: ./entry.php');
    // //         exit;
    // //     } elseif ($post['action'] == 'edit') {
    // //         header('Location: ./edit.php');
    // //         exit;
    // //     }
    // // }

    // if (empty($post['item_name'])) {
    //     $_SESSION['err_msg'] = '項目名が空白です。';
    //     if ($post['action'] == 'entry') {
    //         header('Location: ./entry.php');
    //         exit;
    //     } elseif ($post['action'] == 'edit') {
    //         header('Location: ./edit.php');
    //         exit;
    //     }
    // }

    // if (strlen($post['item_name']) > 100) {
    //     $_SESSION['err_msg'] = '項目名は１００文字以下で登録してください。';
    //     if ($post['action'] == 'entry') {
    //         header('Location: ./entry.php');
    //         exit;
    //     } elseif ($post['action'] == 'edit') {
    //         header('Location: ./edit.php');
    //         exit;
    //     }
    // }

    // 登録・修正・削除
    switch ($post['action']) {
            // 登録
        case 'entry':
            $db = new Users();
            $db->insertUser($post['login_user'], $post['pass'], $post['family_name'], $post['first_name'], $post['is_admin'], $post['is_deleted']);

            header('Location: ./index.php');
            exit;
            // 修正
        case 'edit':
            $db = new Users();
            $db->editUser($post['user_id'], $post['login_user'], $post['pass'], $post['family_name'], $post['first_name'], $post['is_admin'], $post['is_deleted']);

            header('Location: ./index.php');
            exit;
            // 削除
        case 'delete':
            $db = new Users();
            $db->deleteUser($post['user_id']);

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
