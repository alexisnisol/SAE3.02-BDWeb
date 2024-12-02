<?php

namespace Config;

class ConfigBD {
    public static $DB_HOST = 'localhost';
    public static $DB_NAME = 'pony_club';
    public static $DB_USER = 'root';
    public static $DB_PASSWORD = '';

    public static function getConfig() {
        return [
            'db_host' => self::$DB_HOST,
            'db_name' => self::$DB_NAME,
            'db_user' => self::$DB_USER,
            'db_pass' => self::$DB_PASSWORD
        ];
    }
}

?>