<?php

session_start();

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require_once ROOT . '/_inc/auth.php';

$_SESSION['user_id'] = null;
$_SESSION['user_email'] = null;
$_SESSION['user_name'] = null;

//session_destroy();

header('Location: /index.php');
?>