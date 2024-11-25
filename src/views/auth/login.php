<?php

    require_once '../../_inc/auth.php';

    //if is post request
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //get post data
        $email = $_POST['email'];
        $password = $_POST['password'];
        $error = checkLoginForm($email, $password);
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/auth.css">
    <title>Connexion</title>
</head>
<body>
    <div class="login-container">
        <h2>Se connecter</h2>
        <form action="./login.php" method="post">
            <div class="input-container">
                <input name="email" type="email" placeholder="Adresse mail" required>
            </div>
            <div class="input-container">
                <input name="password" type="password" placeholder="Mot de passe" required>
            </div>
            <?php 
            if (isset($error)) {
                echo '<p class="error-message">*' . $error . '</p>';
            }
            ?>
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <a href="/views/accueil/accueil.php">Accueil (verif est login)</a>
    <a href="/views/auth/logout.php">Se déconnecter</a>
</body>
</html>
