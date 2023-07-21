<?php

require_once('../App/Model/Base.php');
require_once('../App/Model/Users.php');
require_once('../App/Util/Safety.php');

unset($_SESSION['err_msg']);

$post = Safety::sanitize($_POST);

if(!isset($post['token']) || !Safety::isValidToken($post['token'])){
    $_SESSION['err_msg'] = '不正な処理が行われました。';
    header('Location: ./index.php');
    exit;
}

$_SESSION['login'] = $post;

try {
    $db = new Users();
    $user = $db->loginUser($post['user']);

    if (!password_verify($post['password'], $user['pass']) || empty($user)) {
        $_SESSION['err_msg'] = 'ユーザー名またはパスワードが違います。';
        $_SESSION['user'] = $post['user'];
        header('Location: ./index.php');
        exit;
    }

    $_SESSION['user'] = $user;
    
    unset($_SESSION['post']);
    header('Location: ../todo/index.php');
    exit;
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
