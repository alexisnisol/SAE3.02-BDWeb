<?php

namespace App\Database;

use PDO;
use PDOException;

class SQLiteDatabase implements Database
{
    private $db_file;
    private $pdo;

    public function __construct($db_file) {
        $this->db_file = $db_file;

        $this->getPDO();
    }

    private function getPDO() {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO("sqlite:{$this->db_file}");
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connexion à la BD échouée : ' . $e->getMessage();
            }
        }
        return $this->pdo;
    }

    public function query($statement) {
        $pdo = $this->getPDO();
        return $pdo->query($statement);
    }

    public function execute($statement) {
        $pdo = $this->getPDO();
        return $pdo->exec($statement);
    }

    public function prepare($statement, $options = []) {
        $pdo = $this->getPDO();
        return $pdo->prepare($statement, $options);
    }

    public function loadContents() {
        if (!$this->databaseExists()) {
            $this->createDatabase();
        }
    }

    public function databaseExists() {
        $query = $this->query("SHOW TABLES LIKE 'PONEY'");
        $result = $query->fetch();
        return $result;
    }

    public function createDatabase() {
        $this->execute(file_get_contents(ROOT . '/static/data/creaPon.sql'));
        $this->execute(file_get_contents(ROOT . '/static/data/insPon.sql'));
    }
}

?>