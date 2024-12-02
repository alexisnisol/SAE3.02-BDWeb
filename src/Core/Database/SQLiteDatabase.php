<?php

namespace Core\Database;

use PDO;

class SQLiteDatabase implements Database
{
    private $db_file;
    private $pdo;

    public function __construct($db_file) {
        $this->db_file = $db_file;
    }

    private function getPDO()
    {
        if ($this->pdo === null) {
            $pdo = new PDO("sqlite:{$this->db_file}");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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