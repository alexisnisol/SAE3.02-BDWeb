<?php

namespace App\Database;

interface Database {
    public function query($statement);
    public function execute($statement);
    public function prepare($statement, $options = []);

    public function loadContents();
    public function databaseExists();
    public function createDatabase();
}
?>