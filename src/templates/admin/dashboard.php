<div class="page">
    <div class="form-container">
        <h1 style="margin-bottom:1rem">Dashboard - Moniteur</h1>
        <button onclick="window.location='index.php?action=creation_cours';">Créer un cours programmé</button>
        <button onclick="window.location='index.php?action=creation_cours_realise';">Ajouter un créneau de cours</button>
        <button onclick="window.location='index.php?action=creation_poney';">Ajouter un poney</button>

        <?php

        use App\Controllers\Auth\Auth;

        if (Auth::isAdmin()) {
        ?>
        <h1 style="margin-bottom:1rem;margin-top:2rem;">Dashboard - Admin</h1>
        <button onclick="window.location='index.php?action=ajouter_moniteur';">Ajouter un moniteur</button>
        <button onclick="window.location='index.php?action=retirer_moniteur';">Retirer un moniteur</button>
        <?php
        }
        ?>
    </div>
</div>