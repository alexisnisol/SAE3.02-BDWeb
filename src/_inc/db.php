<?php
    session_start();

    $bd = new PDO('sqlite:'.__DIR__.'/../db.sqlite');
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /**
     * Crée les tables et insère les données initiales dans la base de données.
     */
    function create_tables() {
        global $bd;
        
        $base = file_get_contents(__DIR__ . '/../static/data/creaPon.sql');
        $insertion = file_get_contents(__DIR__ . '/../static/data/insPon.sql');    

        try {
            $bd->exec($base); // Créer les tables
            $bd->exec($insertion); // Insérer les données
        } catch (PDOException $e) {
            echo "Erreur lors de la creation de la table : " . $e->getMessage();
        }
    }

    /**
     * Récupère le planning hebdomadaire des cours, incluant les détails 
     * des cours réalisés et le nombre de places restantes.
     * 
     * @return array Planning hebdomadaire formaté.
     */
    function get_weekly_schedule() {
        global $bd;

        $stmt = $bd->query("
            SELECT 
                cp.jour, 
                cp.heure, 
                cp.nom_cours, 
                cp.niveau, 
                cp.duree, 
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

    /**
     * Récupère les informations d'un utilisateur à partir de son email.
     *
     * @param string $email Adresse email de l'utilisateur.
     * @return array|null Informations de l'utilisateur ou null si non trouvé.
     */
    function get_user($email) {
        global $bd;

        $stmt = $bd->prepare("SELECT * FROM PERSONNE WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    function get_Poney_Dispo($date) {
        global $bd;
    
        $stmt = $bd->prepare("
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données envoyées en JSON
        $data = json_decode(file_get_contents('php://input'), true);
    
        // Vérification de l'action demandée
        if (isset($data['action']) && $data['action'] === 'get_poney_dispo') {
            // Traitement pour récupérer les poneys disponibles
            $date = $data['date'];
            $heure = $data['heure'];
            $dateH = $date . ' ' . $heure;
            $poneys = get_Poney_Dispo($dateH);
    
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
            if (check_user_inscrit($id_cours, $id_client)) {
                echo json_encode(['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours']);
            } else {
    
            $places_restantes = get_Places_Restantes($id_cours);

            
                
            if ($places_restantes > 0) {
                try {
                insert_reservation($id_client, $id_cours, $id_poney, $date);
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

    


    function insert_reservation($id_client, $id_cours, $id_poney, $date) {
        global $bd;
    
        if (get_Places_Restantes($id_cours) > 0) {
            
        $stmt = $bd->prepare("INSERT INTO RESERVER (id_client, id_cours, id_poney, dateR) VALUES (:id_client, :id_cours, :id_poney, :date)");
        $stmt->execute([
            'id_client' => $id_client,
            'id_cours' => $id_cours,
            'id_poney' => $id_poney,
            'date' => $date
        ]);

        }
    }
    


    function get_Places_Restantes($id_cours) {
        global $bd;
    
        $stmt = $bd->prepare("
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
    function check_user_inscrit($id_cours, $id_client) {
        global $bd;

        $stmt = $bd->prepare("SELECT * FROM RESERVER WHERE id_cours = :id_cours AND id_client = :id_client");
        $stmt->execute(['id_cours' => $id_cours, 'id_client' => $id_client]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    
?>