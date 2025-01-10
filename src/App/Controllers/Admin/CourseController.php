<?php

namespace App\Controllers\Admin;

use App;
use App\Views\Flash;

class CourseController {

    /**
     * Vérifie les données du formulaire de création de cours.
     */
    static function checkCourseCreationForm($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max): string
    {
        $error = '';
        if (empty($nom_cours) || empty($niveau) || empty($duree) || empty($heure) || empty($jour) || empty($ddd) || empty($ddf) || empty($nb_personnes_max)) {
            $error = 'Veuillez remplir tous les champs';
        }
        return $error;
    }

    static function createCourse($nom_cours, $niveau, $duree, $heure, $jour, $ddd, $ddf, $nb_personnes_max)
    {
        $db = App::getApp()->getDB();
        $query = $db->prepare('INSERT INTO COURS_PROGRAMME (nom_cours, niveau, duree, heure, jour, ddd, ddf, nb_personnes_max) VALUES (:nom_cours, :niveau, :duree, :heure, :jour, :date_debut, :date_fin, :nb_personnes_max)');
        $query->execute(array(':nom_cours' => $nom_cours, ':niveau' => $niveau, ':duree' => $duree, ':heure' => $heure, ':jour' => $jour, ':date_debut' => $ddd, ':date_fin' => $ddf, ':nb_personnes_max' => $nb_personnes_max));

        Flash::popup('Cours créé avec succès !', '/index.php?action=dashboard');
    }
}