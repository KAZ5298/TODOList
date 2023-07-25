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

$url = $_SERVER['HTTP_REFERER'];

try {

    // バリデーションチェック（登録・修正のみ）

    if ($post['action'] == 'entry' || $post['action'] == 'edit') {

        // 空白チェック
        // ログインユーザー
        if (!Validation::itemNullCheck($post['login_user'])) {
            $_SESSION['err_msg'] = 'ログインユーザー名が空白です。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // パスワード
        if (!Validation::itemNullCheck($post['pass'])) {
            $_SESSION['err_msg'] = 'パスワードが空白です。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // ユーザー姓
        if (!Validation::itemNullCheck($post['family_name'])) {
            $_SESSION['err_msg'] = 'ユーザー姓が空白です。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // ユーザー名
        if (!Validation::itemNullCheck($post['first_name'])) {
            $_SESSION['err_msg'] = 'ユーザー名が空白です。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // 文字数チェック
        // ログインユーザー
        if (!Validation::userLenChk($post['login_user'])) {
            $_SESSION['err_msg'] = 'ログインユーザー名は５０文字以下で入力してください。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // ユーザー姓
        if (!Validation::userLenChk($post['family_name'])) {
            $_SESSION['err_msg'] = 'ユーザー姓は５０文字以下で入力してください。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // ユーザー名
        if (!Validation::userLenChk($post['first_name'])) {
            $_SESSION['err_msg'] = 'ユーザー名は５０文字以下で入力してください。';
            header("Location: ${url}", true, 307);
            exit;
        }

        // パスワードチェック　半角英数字大文字小文字含む８文字以上５０文字以下
        if (!Validation::passChk($post['pass'])) {
            $_SESSION['err_msg'] = 'パスワードは半角英数大文字小文字含む８文字以上５０文字以下で入力してください。';
            header("Location: ${url}", true, 307);
            exit;
        }

    }

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
