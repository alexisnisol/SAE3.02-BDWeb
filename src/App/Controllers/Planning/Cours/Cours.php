<?php

namespace App\Controllers\Planning\Cours;

use DateTime;

/**
 * Classe représentant un cours avec ses caractéristiques et son affichage.
 */
class Cours {
    public $id_cours;
    public $jour;
    public $date;
    public $heure;
    public $duree;
    public $nom_cours;
    public $niveau;
    public $nb_personnes_max;
    public $nom_moniteur;
    public $participants = [];

    public function __construct($jour, $heure, $duree, $nom_cours, $niveau, $nb_personnes_max, $nom_moniteur,$date, $id_cours) {
        $this->jour = $jour;
        $this->heure = $heure;
        $this->duree = $duree;
        $this->nom_cours = $nom_cours;
        $this->niveau = $niveau;
        $this->nom_moniteur = $nom_moniteur;
        $this->nb_personnes_max = $nb_personnes_max;
        $this->date = $date;
        $this->id_cours = $id_cours;
    }

    public function getHeure() {
        return $this->heure;
    }

    public function getDuree() {
        return $this->duree;
    }

    public function getJour() {
        return $this->jour;
    }

    public function getNomCours() {
        return $this->nom_cours;
    }

    public function getHeureFin() {
        return $this->heure + $this->duree;
    }


    /**
     * Représentation HTML du cours.
     * 
     * @return string Code HTML représentant le cours.
     */
    public function __repr__() {
        $unformattedDate = new DateTime($this->date);
        $formattedDate = date('d/m/Y', strtotime($this->date));
        $startTime = new DateTime($this->heure);
        $formattedTime = $startTime->format('H:i:s');
    
        $endTime = clone $startTime;
        $endTime->modify("+{$this->duree} hours");
        $formattedTimeFin = $endTime->format('H:i:s');


        return "
            <div class='course' onclick='openBookingPopup(" . json_encode([
                'nom_cours' => $this->nom_cours,
                'moniteur' => $this->nom_moniteur,
                'nb_personnes_max' => $this->nb_personnes_max,
                'date' => $unformattedDate->format('Y-m-d'),
                'heure' => $formattedTime,
                'heureFin' => $formattedTimeFin,
                'id_cours' => $this->id_cours
            ]) . ")'>
                <h4>" . htmlspecialchars($this->nom_cours) . "</h4>
                <p><strong>Capacité Maximale:</strong> " . htmlspecialchars($this->nb_personnes_max) . "</p>
                <p><strong>Moniteur :</strong> " . htmlspecialchars($this->nom_moniteur) . "</p>
            </div>";
    }
    
}

?>