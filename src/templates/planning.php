<?php

use App\Controllers\Auth\Auth;
use App\Controllers\Planning\Planning;
use App\Controllers\Planning\WeekNavigator;

$week = isset($_GET['week']) ? (int)$_GET['week'] : (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$planning = new Planning($week, $year);
try {
    $planning->generatePlanning();
} catch (DateMalformedStringException $e) {
    echo $e->getMessage();
    die();
}

$weekNavigator = new WeekNavigator($week, $year);

$user = Auth::getCurrentUserObj();
?>

<<<<<<< HEAD
<section class="gloabal">
=======
<section class="global">
>>>>>>> 2e390e20a809fff530426e61dbf35fd44a34ae39
<aside class="client-info">
    <div class="profile-pic">
        <img src="/static/images/client.webp" alt="img de profil">
    </div>
    <div class="info">
        <h3>Infos personnelles</h3>
        <p><strong>Nom : </strong> <?= $user->firstName ?></p>
        <p><strong>Prénom : </strong> <?= $user->lastName ?></p>
        <p><strong>Email : </strong> <?= $user->email ?></p>
        <p><strong>Téléphone : </strong> <?= $user->phone ?></p>
<<<<<<< HEAD
        <p><strong>Niveau : </strong><?= $user->level ?></p>
        <p><strong>Date Inscription : </strong><?= $user->date_inscription ?></p>
        <p><strong>Est payé : </strong><?= $user->cotisation ?></p>
    </div>
</aside>
<main>
=======
        <p><strong>Niveau : </strong><?= $user->getLevel()?></p>
        <p><strong>Date Inscription : </strong><?= $user->date_inscription ?></p>
    </div>
</aside>
<main>
<div class="planning-container">
>>>>>>> 2e390e20a809fff530426e61dbf35fd44a34ae39
<h1 class="planning_titre">Planning Hebdomadaire</h1>

<div class="planning">
    <!-- Ligne des jours et dates -->
    <div class="header"></div>
    <?php
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $dateCourante = new DateTime();
    $dateCourante->setISODate($year, $week);


    foreach ($jours as $index => $jour) {
        $date = $dateCourante->format('d/m');
        echo "<div class='day-header' style='grid-column: " . ($index + 2) . "; grid-row: 1;'>";
        echo "$jour<br>$date";
        echo "</div>";
        $dateCourante->modify('+1 day');
    }
    ?>

    <!-- Colonne des heures -->
    <?php for ($hour = 8; $hour <= 20; $hour++): ?>
        <div class="hour"><?= $hour ?>:00</div>
    <?php endfor; ?>

    <!-- Planning dynamique -->
    <?= $planning->renderPlanning() ?>


</div>
    <!-- Week Navigation -->
    <?= $weekNavigator->renderNavigation() ?>

</main>
<!-- Le pop-up pour la réservation -->
<div class="popup" id="booking-popup">
    <div class="popup-content">
        <h2 id="popup-title">Réservation pour le cours</h2>
        <p id="popup-course-info"></p>
        <p><strong>Date et Heure :</strong> <span id="popup-date-time"></span></p>
        <label for="poney"> <strong>Choisissez un poney : </strong></label>
        <select name="poney_dispo" id="poney_dispo">

        </select>


        <div class="button-container">
            <button class="cancel-btn" onclick="closeBookingPopup()">Annuler</button>

            <form id="booking-form">
                <input type="hidden" name="id_user" id="id_user" value="<?= $user->id ?>">
                <input type="hidden" name="id_cours" id="id_cours">
                <input type="hidden" name="id_poney" id="id_poney">
                <input type="hidden" name="date" id="dateC">
                <button type="submit" class="book-btn">Réserver</button>


            </form>
        </div>
    </div>
</div>
</section>

<script src= "/static/js/Pop_Up_Reserver.js">
<<<<<<< HEAD
</script>
=======
</script>
</div>
>>>>>>> 2e390e20a809fff530426e61dbf35fd44a34ae39
