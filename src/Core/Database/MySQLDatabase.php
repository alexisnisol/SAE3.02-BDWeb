<?php

namespace Core\Database;

use PDO;
use PDOException;

class MySQLDatabase implements Database
{
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_user = 'root', $db_pass = 'root', $db_host = 'localhost') {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;

        $this->getPDO();
    }

    private function getPDO()
    {
        if ($this->pdo === null) {
            try {
                $pdo = new PDO(
                    "mysql:dbname={$this->db_name};host={$this->db_host}",
                    $this->db_user,
                    $this->db_pass
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

    public function prepare($statement, $options = []) {
        $pdo = $this->getPDO();
        return $pdo->prepare($statement, $options);
    }
}

?>