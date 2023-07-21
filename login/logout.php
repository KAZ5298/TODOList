<?php

require_once('../App/Model/Base.php');

unset($_SESSION['user']);
unset($_SESSION['post']);
unset($_SESSION['err_msg']);

header('Location: ./');
exit;

?>