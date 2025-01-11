<?php

namespace App\Controllers\Planning;

use PDOException;
use App\Controllers\Planning\PlanningDB;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées en JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérification de l'action demandée
    if (isset($data['action']) && $data['action'] === 'getPoneyDispo') {
        // Traitement pour récupérer les poneys disponibles
        $date = $data['date'];
        $heure = $data['heure'];
        $dateH = $date . ' ' . $heure;
        $poneys = PlanningDB::getPoneyDispo($dateH);

        echo json_encode([
            'success' => true,
            'poneys' => $poneys
        ]);
    }
    elseif (isset($data['id_user'], $data['id_cours'], $data['id_poney'], $data['date'])) {
        // Traitement pour la réservation
        $id_client = $data['id_user'];
        $id_cours = $data['id_cours'];
        $id_poney = $data['id_poney'];
        $date = $data['date'];
        if (PlanningDB::check_user_inscrit($id_cours, $id_client)) {
            echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours']);
        } else {
            $places_restantes = PlanningDB::getPlacesRestantes($id_cours);

            $niveau_client = PlanningDB::getNiveauClient($id_client);
            $niveau_cours = PlanningDB::getNiveauCours($id_cours);
            
            if ($niveau_client < $niveau_cours) {
                echo json_encode(['success' => false, 'message' => 'Votre niveau est insuffisant pour ce cours']);
            } elseif ($places_restantes > 0) {
                try {
                    PlanningDB::addReservation($id_client, $id_cours, $id_poney, $date);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Réservation effectuée',
                        
                    ]);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Erreur lors de la réservation']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Plus de places disponibles']);
            }
        }
    }
}