<?php

use App\Controllers\Auth\Auth;
use App\Views\Flash;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ce811b00f8.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./static/css/header.css">
    <link rel="stylesheet" href="./static/css/footer.css">
    <?php
    if (!empty($cssFiles)) {
        foreach ($cssFiles as $cssFile) {
            echo '<link rel="stylesheet" href="./static/css/' . $cssFile . '">';
        }
    }
    ?>
    <title><?php echo $title ?? null ?></title>
</head>
<body>

<header>
    <div class="logo">
        <img src="static/images/logo.png" alt="Logo">
        <h1>Les Cavaliers de L'IUT</h1>
    </div>
    <nav class="nav-menu">
        <ul>
            <li><a href="./index.php"
                   class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] == 'home')) ? 'active' : ''; ?>">Accueil</a>
            </li>
            <?php
            if (!Auth::isUserLoggedIn()) {
                ?>
                <li><a href="index.php?action=register"
                       class="<?php echo (isset($_GET['action']) && $_GET['action'] == 'register') ? 'active' : ''; ?>">Inscription</a>
                </li>
                <?php
            } else {
                ?>
                <li><a href="index.php?action=planning"
                       class="<?php echo (isset($_GET['action']) && $_GET['action'] == 'planning') ? 'active' : ''; ?>">Réservation</a>
                </li>
                <li><a href="index.php?action=coursReserver"
                       class="<?php echo (isset($_GET['action']) && $_GET['action'] == 'coursReserver') ? 'active' : ''; ?>">
                        Mes Cours</a></li>
                <?php
            }
            ?>
        </ul>
    </nav>
    <div class="actions">
        <?php
        if (Auth::isUserLoggedIn()) {
            echo '<p>Bonjour, ' . Auth::getCurrentUser()['name'] . '</p>';

            if (Auth::isInstructor()) {
                echo '<a href="index.php?action=dashboard">Dashboard</a>';
            }

            echo '<a href="index.php?action=logout">Déconnexion</a>';
        } else {
            echo '<a href="index.php?action=login">Connexion</a>';
        }
        ?>
    </div>
</header>


<main>
    <?php Flash::flash();?>
    <?php echo $content ?? null ?>
</main>

<footer>
    <div class="col1">
        <H3>
            Les Cavaliers de l'IUT
        </H3>
        <img src="static/images/logo.png" alt="logo">
    </div>

    <div class="col2">
        <H3>
            Contacts
        </H3>
        <p>02 38 49 44 62</p>
        <p>Rue d'issoudun, 45067 Orléans cedex 02</p>

    </div>
    <div class="col3">
        <H3>
            Membres de l'équipe
        </H3>
        <ul>
            <li>Mouad Zouadi</li>
            <li>Alexy Wiciak</li>
            <li>Alexis Nisol</li>
        </ul>

    </div>
    <div class="horizontal">
        <p><strong>&copy; 2024 - Tous droits réservés</strong></p>
    </div>

</footer>
</body>
</html>