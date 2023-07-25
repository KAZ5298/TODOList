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
    $db = new Common();
    $dt = $db->getDate();

    $db = new TodoItems();
    $db->todoItemIsComplete($dt, $post['item_id']);

    header('Location: ./index.php');
    exit;
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}