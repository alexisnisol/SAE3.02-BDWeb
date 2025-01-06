<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require ROOT . '/App/App.php';

App::loadApp();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/index.css">
    <link rel="stylesheet" href="./static/css/header.css">
    <link rel="stylesheet" href="./static/css/footer.css">
    <title>Accueil</title>
</head>
<body>
<?php include '_inc/header.php'?>
    <!-- Section avec l'image de fond, h1 et paragraphe -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="presentation-title">BIENVENUE CHEZ LES PETITS CAVALIERS DE L’IUT !</h1>
            <p>Découvrez la joie de l’équitation, une aventure d’évasion unique et de complicité avec nos poneys, idéale pour petits et grands, le tout dans un cadre convivial, chaleureux et en pleine nature !</p>
            <a href="#" class="btn">Rejoignez-nous !</a>
        </div>
    </section>

    <!-- Première section avec texte à gauche et image à droite -->
    <section class="section-with-image-right">
        <div class="text1">
            <h1>LA VIE DANS NOTRE CLUB</h1>
            <p class="p1">Plongez dans un univers équestre unique où passion, nature et complicité se rencontrent. Situé en plein cœur de la campagne, le Club de Poney Évasion est l’endroit idéal pour tous les amoureux des chevaux, petits et grands.</p>
            <p class="p1">Que vous soyez débutant ou cavalier confirmé, notre club vous propose une large gamme d'activités adaptées à tous les niveaux.</p>
            <p class="p1">Nos moniteurs passionnés et diplômés vous accompagnent à chaque étape, que ce soit pour des balades en pleine nature, des cours d’apprentissage ou des stages intensifs pour les plus ambitieux.</p>
            <a href="#" class="btn">En savoir plus</a>
        </div>
        <div class="image-container">
            <img class="image2" src="./static/images/image2.png" alt="image2">
        </div>
    </section>

    <!-- Section du carrousel des poneys -->
    <div class="section-poneys">
        <h1 class="titre-poneys">Présentation de nos poneys</h1>
        <div class="poney-carousel">
            <button class="carousel-btn prev-btn" onclick="movePoney(-1)">&#10094;</button>
            <div class="carousel-track-container">
                <div class="carousel-track">
                    <!-- Exemple de poney -->
                    <div class="poney-box">
                        <img src="./static/images/poney1.png" alt="Poney 1">
                        <div class="poney-description">
                            <h2>Noisette</h2>
                            <p>Petit, mais énergique, Noisette est un poney joueur qui adore explorer de nouveaux chemins. Sa robe marron chocolat et son caractère espiègle en font le favori des plus jeunes.</p>
                        </div>
                    </div>
                    <div class="poney-box">
                        <img src="./static/images/poney2.jpeg" alt="Poney 2">
                        <div class="poney-description">
                            <h2>Choco</h2>
                            <p>Choco est connu pour sa nature calme et douce. Idéal pour les balades tranquilles et les moments de complicité.</p>
                        </div>
                    </div>
                    <div class="poney-box">
                        <img src="./static/images/poney3.jpeg" alt="Poney 3">
                        <div class="poney-description">
                            <h2>Caramel</h2>
                            <p>Avec une énergie débordante, Caramel est parfait pour les jeunes cavaliers qui aiment l'action et l'aventure.</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-btn next-btn" onclick="movePoney(1)">&#10095;</button>
            <script src="./static/js/carousel.js"></script>
        </div>
    </div>
    <?php include '_inc/footer.php'?>
</body>
</html>
