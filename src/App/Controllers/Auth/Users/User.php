<?php

namespace App\Controllers\Auth\Users;

use App;

class User {

    public $id;

    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $phone;
    public $level;
    public $weight;
    public $password;
    public $date_inscription;

    public $salaire;
    public $experience;

    public function __construct($id, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password, $date_inscription){
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->address = $address;
        $this->email = $email;
        $this->phone = $phone;
        $this->level = $level;
        $this->weight = $weight;
        $this->password = $password;
        $this->date_inscription = $date_inscription;
    }

    public function isInstructor() {
        return false;
    }

    
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function register(){
        $this->hashPassword();
        $query = App::getApp()->getDB()->prepare('INSERT INTO PERSONNE (nom, prenom, adresse, telephone, email, mdp, poids, niveau) VALUES (:nom, :prenom, :adresse, :telephone, :email, :mdp, :poids, :niveau)');
        $query->execute(array(':nom' => $this->lastName, ':prenom' => $this->firstName, ':adresse' => $this->address, ':telephone' => $this->phone, ':email' => $this->email, ':mdp' => $this->password, ':poids' => $this->weight, ':niveau' => $this->level));
    }
}

?>