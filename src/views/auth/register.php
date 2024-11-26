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
    <link rel="stylesheet" href="/static/css/header.css">
    <link rel="stylesheet" href="/static/css/footer.css">
    <title>Inscription</title>
</head>
<body>
    <?php include '../../_inc/header.php'?>
    <main class="login">
        <div class="login-container">
            <h2>S'inscrire maintenant</h2>
            
            <form action="#" method="post">
                <div class="input-row">
                    <div class="input-container">
                        <input type="text" placeholder="Prénom" required>
                    </div>
                    <div class="input-container">
                        <input type="text" placeholder="Nom" required>
                    </div>
                </div>
                
                <div class="input-container">
                    <input type="text" placeholder="Adresse" required>
                </div>
                
                <div class="input-container">
                    <input type="tel" placeholder="Numéro de téléphone" required>
                </div>
                
                <div class="input-container">
                    <input type="email" placeholder="Adresse mail" required>
                </div>

                <div class="input-row">
                    <div class="input-container">
                        <select required>
                            <option value="" disabled selected>Niveau</option>
                            <option value="debutant">Débutant</option>
                            <option value="intermediaire">Intermédiaire</option>
                            <option value="avance">Avancé</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <input type="number" placeholder="Poids" required>
                    </div>
                </div>
                <div class="input-container">
                    <input type="text" placeholder="Coût de l'inscription : xx" required>
                </div>
                <button type="submit">S'inscrire</button>
            </form>

            <a href="./login.php" class="register-link">Déjà un compte ? Connectez-vous</a>
        </div>
    </main>
</body>

<?php include '../../_inc/footer.php'?>
</html>
