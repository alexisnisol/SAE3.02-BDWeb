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
                cp.nb_personnes_max - COUNT(r.id_client) AS places_restantes, 
                p.nom || ' ' || p.prenom AS nom_moniteur,
                cr.dateR
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
?>
