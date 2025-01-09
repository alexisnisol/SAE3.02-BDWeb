<?php
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
require ROOT . '/_inc/db.php';
require ROOT . '/_inc/cours.php';
require ROOT . '/_inc/ConteneurCours.php';
require ROOT . '/_inc/caseDouble.php';
require ROOT . '/_inc/caseSimple.php';
require ROOT . '/_inc/navigation.php';



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
                    $coursData['nb_personnes_max'],
                    $coursData['nom_moniteur'],
                    $coursData['dateR'],
                    $coursData['id_cp']
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

                echo "<div class='course-case' style='grid-column: $jourIndex; grid-row: $startRow / span $rowSpan'>";
                echo $case->__repr__();
                echo "</div>";
            }
        }
     ?>

</div>


<?php if (isset($week, $year)) {
    $navigator = new WeekNavigator($week, $year);
    echo $navigator->renderNavigation();
    } else {
        echo '<p>Les paramètres semaine et année ne sont pas définis.</p>';
    }
?>
</main>
</section>
<?php include ROOT . '/_inc/footer.php' ?>


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
                <input type="hidden" name="id_user" value="<?= $user['id_p'] ?>">
                <input type="hidden" name="id_cours" id="id_cours">
                <input type="hidden" name="id_poney" id="id_poney">
                <input type="hidden" name="date" id="dateC">
                <button type="submit" class="book-btn">Réserver</button>

                
            </form>
        </div>
    </div>
</div>

<script>
    function openBookingPopup(courseInfo) {
    const popupTitle = document.getElementById('popup-title');
    const courseInfoElem = document.getElementById('popup-course-info');
    const dateTimeElem = document.getElementById('popup-date-time');

    // Affichage des informations du cours
    popupTitle.textContent = "Réservation pour le cours : " + courseInfo.nom_cours;
    courseInfoElem.innerHTML = `
        <p><strong>Cours :</strong> ${courseInfo.nom_cours}</p>
        <p><strong>Moniteur :</strong> ${courseInfo.moniteur}</p>
        <p><strong>Capacité Maximale:</strong> ${courseInfo.nb_personnes_max}</p>
    `;
    dateTimeElem.textContent = `${courseInfo.date} de ${courseInfo.heure} à ${courseInfo.heureFin}`;

    document.getElementById('id_cours').value = courseInfo.id_cours;
    document.getElementById('dateC').value = `${courseInfo.date} ${courseInfo.heure}`;

    // Appel à la fonction PHP pour obtenir les poneys disponibles
    fetch('/_inc/db.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'get_poney_dispo',
            date: courseInfo.date,
            heure: courseInfo.heure
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const poneys = data.poneys;
            const poneySelect = document.getElementById('poney_dispo');
            poneySelect.innerHTML = ''; // Réinitialise la liste

            // Ajoute chaque poney à la liste déroulante
            poneys.forEach(poney => {
                const option = document.createElement('option');
                option.value = poney.id;
                option.textContent = `${poney.nom} - ${poney.poids_max} kg - ${poney.age} ans`;
                poneySelect.appendChild(option);
            });
        } else {
            alert('Erreur lors du chargement des poneys disponibles.');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la récupération des poneys disponibles.');
    });

    // Affichage du pop-up
    document.getElementById('booking-popup').style.display = 'flex';
}



    function closeBookingPopup() {
        document.getElementById('booking-popup').style.display = 'none';
    }
    document.getElementById('booking-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche l'envoi classique du formulaire

    const id_user = 1; // ID utilisateur récupéré côté PHP
    const id_cours = document.getElementById('id_cours').value;
    const id_poney = document.getElementById('poney_dispo').value;
    const date = document.getElementById('dateC').value;


    // Appel de la fonction pour insérer la réservation via une requête POST
    fetch('/_inc/db.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id_user, id_cours, id_poney, date })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Réponse du serveur:', data.message);
            alert('Réservation effectuée avec succès!');
        
            
            closeBookingPopup(); // Fermeture du pop-up
        } else {
            alert('Erreur lors de la réservation: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la réservation.'+ error);
    });
});

</script>


</body>
</html>
