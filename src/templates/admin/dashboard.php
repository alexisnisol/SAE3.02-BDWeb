<?php
use App\Controllers\Planning\PlanningDB;
  
$poneys = PlanningDB::getAllPoneys();
?>

<div class="page">
    <div class="form-container">
        <h1 style="margin-bottom:1rem">Dashboard - Moniteur</h1>
        <button onclick="window.location='index.php?action=creation_cours';">Créer un cours programmé</button>
        <button onclick="window.location='index.php?action=creation_cours_realise';">Ajouter un créneau de cours</button>
        <button onclick="window.location='index.php?action=creation_poney';">Ajouter un poney</button>
        
        <!-- Bouton pour afficher la liste déroulante -->
        <button id="show-ponney-form" style="margin-top: 1rem;">Voir le planning d'un poney</button>

        <!-- Section cachée au départ -->
        <div id="ponney-form" style="display: none; margin-top: 1rem;">
            <form action="index.php?action=planningPoney" method="get">
                <input type="hidden" name="action" value="planning_ponney">

                <label for="poney">Choisir un poney :</label>
                <select name="poney" id="poney" required>
                    <option value="" disabled selected>-- Sélectionnez un poney --</option>
                    <?php foreach ($poneys as $poney): ?>
                        <option value="<?= $poney['id_poney'] ?>"><?= $poney['nom'] ?> <?=$poney['age']?> <?=$poney['poids']?></option>
                </select>
                
                <button type="submit" style="margin-top: 1rem;">Voir le planning</button>
            </form>
        </div>
    </div>
    <script>
    // Ajout d'un gestionnaire d'événement pour afficher la liste déroulante
    document.getElementById('show-ponney-form').addEventListener('click', function() {
        const form = document.getElementById('ponney-form');
        form.style.display = 'block'; // Afficher le formulaire
        this.style.display = 'none'; // Cacher le bouton
    });
    </script>
</div>