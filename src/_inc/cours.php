<?php
/**
 * Classe représentant un cours avec ses caractéristiques et son affichage.
 */
class Cours {
    public $jour;
    public $date;
    public $heure;
    public $duree;
    public $nom_cours;
    public $niveau;
    public $places_restantes;
    public $nom_moniteur;
    public $participants = [];

    public function __construct($jour, $heure, $duree, $nom_cours, $niveau, $nb_personnes_max, $nom_moniteur,$date) {
        $this->jour = $jour;
        $this->heure = $heure;
        $this->duree = $duree;
        $this->nom_cours = $nom_cours;
        $this->niveau = $niveau;
        $this->nom_moniteur = $nom_moniteur;
        $this->places_restantes = $nb_personnes_max;
        $this->date = $date;


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


    public function addParticipant($participant) {
        if ($this->places_restantes <= 0) {
            return false;
        } else {
            $this->participants[] = $participant;
            $this->places_restantes--;
            return true;
        }
    }

    public function getParticipants() {
        return $this->participants;
    }

    public function removeParticipant($participant) {
        $index = array_search($participant, $this->participants);
        if ($index !== false) {
            unset($this->participants[$index]);
        }
    }

    /**
     * Représentation HTML du cours.
     * 
     * @return string Code HTML représentant le cours.
     */
    public function __repr__() {
        return "
            <div class='course'>
                <h4>" . htmlspecialchars($this->nom_cours) . "</h4>
                <p><strong>Places restantes :</strong> " . htmlspecialchars($this->places_restantes) . "</p>
                <p><strong>Moniteur :</strong> " . htmlspecialchars($this->nom_moniteur) . "</p>
            </div>";
    }
    
}

?>