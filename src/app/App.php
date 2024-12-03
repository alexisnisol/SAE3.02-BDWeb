<?php

use Config\ConfigBD;
use Core\Database\MySQLDatabase;

class App {

    private $db;
    private $app;

    public static function getApp() {
        if (is_null(self::$app)) {
            self::$app = new App();
        }

        return self::$app;
    }

    public static function loadApp() {
        session_start();

        require ROOT . '/Core/Autoloader.php';

        \Core\Autoloader::register();

        self::getApp()->loadDB();
    }

    private function loadDB() {
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