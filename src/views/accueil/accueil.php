<?php


require_once '../../_inc/auth.php';

checkUserLoggedIn();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Bonjour </h1>
    <?php
    echo $_SESSION['user_id'];
    ?>
</body>
</html>