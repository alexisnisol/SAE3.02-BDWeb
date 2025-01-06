<?php define('ROOT', $_SERVER['DOCUMENT_ROOT']); ?>

<header>
    <div class="logo">
        <img src="/static/images/logo.png" alt="Logo">
        <h1>Les Cavaliers de L'IUT</h1>
    </div>
    <nav class="nav-menu">
        <ul>
<li><a href="./index.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Accueil</a></li>
        <li><a href="/views/reserver/planning.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'planning.php') ? 'active' : ''; ?>">Planning</a></li>
        <li><a href="inscription.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'inscription.php') ? 'active' : ''; ?>">Inscription</a></li>
            
        </ul>
    </nav>
    <div class="actions">
        <a href="./views/auth/login.php"> Connexion</a>
    </div>
</header>

