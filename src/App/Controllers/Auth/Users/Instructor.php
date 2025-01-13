<?php

namespace App\Controllers\Auth\Users;

class Instructor extends User {

    public function __construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password, $date_inscription, $salaire, $experience){
        parent::__construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password, $date_inscription);
        $this->salaire = $salaire;
        $this->experience = $experience;
    }

    public function isInstructor(): bool
    {
        return true;
    }
}
?>