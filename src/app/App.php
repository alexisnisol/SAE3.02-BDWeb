<?php

use Config\ConfigBD;
use Core\Database\MySQLDatabase;

class App {

    private $db;

    public static function loadApp() {
        session_start();
        self::loadDB();
    }

    public function loadDB() {
        if ($this->db === null) {
            $config = ConfigBD::getConfig();
            $this->db = new MySQLDatabase($config['db_name'], $config['db_user'], $config['db_pass'], $config['db_host']);
        }
    }

    public function getDB() {
        return $this->db;
    }



}

?>