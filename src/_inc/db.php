<?php

session_start();

$bd = new PDO('sqlite:'.__DIR__.'/../db.sqlite');
$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function create_tables(){
    global $bd;
    $bd->exec('
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            email TEXT,
            password TEXT
        )
    ');

    //insert admin user
    $bd->exec('INSERT INTO users (name, email, password) VALUES ("admin", "admin@admin", "' .password_hash('admin', PASSWORD_DEFAULT). '")');
}


?>