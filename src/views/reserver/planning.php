<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
require ROOT . '/_inc/db.php';
require ROOT . '/_inc/cours.php';
require ROOT . '/_inc/cases.php';
require ROOT . '/_inc/caseDouble.php';
require ROOT . '/_inc/caseSimple.php';

create_tables();

$schedule = get_weekly_schedule();
$user = get_user("alice@example.com");

$week = isset($_GET['week']) ? (int)$_GET['week'] : (int)date('W');
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$dateCourante = new DateTime();
$dateCourante->setISODate($year, $week);
$dateDebutSemaine =  (clone $dateCourante)->modify('monday this week');
$dateFinSemaine = (clone $dateCourante)->modify('+6 days');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/css/header.css">
    <link rel="stylesheet" href="/static/css/footer.css">
    <link rel="stylesheet" href="/static/css/planning.css"> 
    <link rel="stylesheet" href="/static/css/navigation.css">

    <title>Planning</title>
</head>
<body>

<?php include ROOT . '/_inc/header.php' ?>

<section class="gloabal">
<aside class="client-info">
    <div class="profile-pic">
        <img src="/static/images/client.webp" alt="img de profil">
    </div>
    <div class="info">
        <h3>Infos personnelles</h3>
        <p><strong>Nom : </strong> <?= $user['nom'] ?></p>
        <p><strong>Prénom : </strong> <?= $user['prenom'] ?></p>
        <p><strong>Email : </strong> <?= $user['email'] ?></p>
        <p><strong>Téléphone : </strong> <?= $user['telephone'] ?></p>
        <p><strong>Niveau : </strong><?= $user['niveau']?></p>
        <p><strong>Date Inscription : </strong><?= $user['date_inscription']?></p>
    </div>
</aside>
<main> 
<h1 class="planning_titre">Planning Hebdomadaire</h1>

<div class="planning">
    <!-- Ligne des jours et dates -->
    <div class="header"></div>
    <?php
    $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $dateCourante = clone $dateDebutSemaine;

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
    <?php
        $planning = [];

        foreach ($schedule as $coursData) {
            $dateCours = new DateTime($coursData['dateR']);

            // Vérification que la date du cours appartient à la semaine affichée
            if ($dateCours >= $dateDebutSemaine && $dateCours <= $dateFinSemaine) {
                $cours = new Cours(
                    $coursData['jour'],
                    $coursData['heure'],
                    $coursData['duree'],
                    $coursData['nom_cours'],
                    $coursData['niveau'],
                    $coursData['places_restantes'],
                    $coursData['nom_moniteur'],
                    $coursData['dateR']
                );

                $jour = $cours->getJour();
                $heure = $cours->getHeure();
                $duree = $cours->getDuree();

                if (!in_array($jour, $jours)) {
                    continue; 
                }

                if ($duree !== 1 && $duree !== 2) {
                    continue;
                }

                if ($planning[$jour][$heure] ?? false) {
                    continue; // Ignorer si la case est déjà occupée
                }

                if ($duree === 2 && $planning[$jour][$heure + 1] ?? false) {
                    continue; // Ignorer si la case double est déjà occupée
                }

                
                

                $case = ($duree === 1) ? new CaseSimple() : new CaseDouble();
                $case->addCours($cours);

                if (!isset($planning[$jour])) {
                    $planning[$jour] = [];
                }
                $planning[$jour][$heure] = $case;
            }
        }

        foreach ($planning as $jour => $cases) {
            foreach ($cases as $heure => $case) {
                $jourIndex = array_search($jour, $jours) + 2;
                $startRow = $heure - 7;
                $rowSpan = $case->getDuration();

                
                echo "<div class='course-case' style='grid-column: $jourIndex; grid-row: $startRow / span $rowSpan;'>";
                echo $case->__repr__();
                echo "</div>";
            }
        }
     ?>

</div>

<?php include ROOT . '/_inc/navigation.php' ?>
</main>
</section>
<?php include ROOT . '/_inc/footer.php' ?>
</body>
</html>
