<?php
session_start();

require_once '../../_inc/auth.php';

$_SESSION['user_id'] = null;
$_SESSION['user_email'] = null;
$_SESSION['user_name'] = null;

//session_destroy();
?>