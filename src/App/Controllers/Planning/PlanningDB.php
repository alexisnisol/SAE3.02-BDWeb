<?php

namespace App\Controllers\Planning;

use App;
use PDO;

class PlanningDB {

/**
 * Récupère le planning hebdomadaire des cours, incluant les détails 
 * des cours réalisés et le nombre de places restantes.
 * 
 * @return array Planning hebdomadaire formaté.
 */
static function getWeeklySchedule() {
    $stmt = App::getApp()->getDB()->query("
        SELECT
            cp.id_cp,
            cp.nom_cours,
            cp.niveau,
            cp.duree,
            cp.heure,
            cp.jour,
            cp.Ddd,
            cp.Ddf,
            cp.nb_personnes_max,
            p.nom || ' ' || p.prenom AS nom_moniteur,
            cr.dateR,
            cp.id_cp
        FROM COURS_PROGRAMME cp
        LEFT JOIN COURS_REALISE cr ON cp.id_cp = cr.id_cours
        LEFT JOIN PERSONNE p ON cr.id_moniteur = p.id_p
        LEFT JOIN RESERVER r ON cp.id_cp = r.id_cours
        GROUP BY
            cp.id_cp, cp.jour, cp.heure, cp.nom_cours, cp.niveau, cp.duree, cp.nb_personnes_max, p.nom, p.prenom, cr.dateR
        ORDER BY
            CASE cp.jour
                WHEN 'Lundi' THEN 1
                WHEN 'Mardi' THEN 2
                WHEN 'Mercredi' THEN 3
                WHEN 'Jeudi' THEN 4
                WHEN 'Vendredi' THEN 5
                WHEN 'Samedi' THEN 6
                WHEN 'Dimanche' THEN 7
                ELSE 8
            END,
            cp.heure
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

static function getPoneyDispo($date) {
    $stmt = App::getApp()->getDB()->prepare("
        SELECT id, nom, poids_max, age 
        FROM PONEY 
        WHERE id NOT IN (
            SELECT id_poney 
            FROM RESERVER 
            WHERE dateR = :date
        )
    ");

    $stmt->execute(['date' => $date]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

static function getNiveauCours($id_cours){
    $stmt = App::getApp()->getDB()->prepare("SELECT niveau FROM COURS_PROGRAMME WHERE id_cp = :id_cours");
    $stmt->execute(['id_cours' => $id_cours]);
    $niveau = $stmt->fetch(PDO::FETCH_ASSOC);
    return $niveau['niveau'];
}

static function getNiveauClient($id_client){
    $stmt = App::getApp()->getDB()->prepare("SELECT niveau FROM PERSONNE WHERE id_p = :id_client");
    $stmt->execute(['id_client' => $id_client]);
    $niveau = $stmt->fetch(PDO::FETCH_ASSOC);
    return $niveau['niveau'];
}


static function addReservation($id_client, $id_cours, $id_poney, $date) {
    if (self::getPlacesRestantes($id_cours) > 0) {
        
    $stmt = App::getApp()->getDB()->prepare("INSERT INTO RESERVER (id_client, id_cours, id_poney, dateR) VALUES (:id_client, :id_cours, :id_poney, :date)");
    $stmt->execute([
        'id_client' => $id_client,
        'id_cours' => $id_cours,
        'id_poney' => $id_poney,
        'date' => $date
    ]);

    }
}

static function getPlacesRestantes($id_cours) {
    $stmt = App::getApp()->getDB()->prepare("
        SELECT 
            cp.nb_personnes_max - IFNULL(COUNT(r.id_client), 0) AS places_restantes
        FROM 
            COURS_PROGRAMME cp
        LEFT JOIN 
            COURS_REALISE cr ON cp.id_cp = cr.id_cours
        LEFT JOIN 
            RESERVER r ON cr.id_cours = r.id_cours
        WHERE 
            cp.id_cp = :id_cours
        GROUP BY 
            cp.nb_personnes_max
    ");
    
    $stmt->execute(['id_cours' => $id_cours]);

    $places_restantes = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification de la valeur renvoyée
    if ($places_restantes && isset($places_restantes['places_restantes'])) {
        return (int) $places_restantes['places_restantes'];
    }

    // Retourne 0 si aucune donnée n'est trouvée
    return 0;
}

static function check_user_inscrit($id_cours, $id_client) {
    $stmt = App::getApp()->getDB()->prepare("SELECT * FROM RESERVER WHERE id_cours = :id_cours AND id_client = :id_client");
    $stmt->execute(['id_cours' => $id_cours, 'id_client' => $id_client]);

    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        return true;
    } else {
        return false;
    }
}
}  
?>