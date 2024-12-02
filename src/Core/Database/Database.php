<?php

namespace Core\Database;

interface Database {
    public function query($statement);
    public function prepare($statement, $options = []);
}
?>