<?php

namespace App\Auth\Users;

class Instructor extends User {

    public function __construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password, $salaire, $experience){
        parent::__construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password);
        $this->salaire = $salaire;
        $this->experience = $experience;
    }

    public function isInstructor() {
        return true;
    }
}
?>