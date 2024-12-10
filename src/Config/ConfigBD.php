<?php

namespace Config;

class ConfigBD {
    public static $DB_HOST = 'servinfo-maria';
    public static $DB_NAME = 'DBnisol';
    public static $DB_USER = 'nisol';
    public static $DB_PASSWORD = 'nisol';

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