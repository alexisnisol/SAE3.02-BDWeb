<?php

namespace App\Auth;

use App;
use App\Auth\Users\Instructor;
use App\Auth\Users\User;

class Auth
{

    static function isUserLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    static function getCurrentUser() {
        if (self::isUserLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email']
            ];
        }
        return null;
    }

    static function getCurrentUserObj() {
        if (self::isUserLoggedIn()) {
            return self::getUserById($_SESSION['user_id']);
        }
        return null;
    }

    static function checkUserLoggedIn() {
        if(!self::isUserLoggedIn()){
            header('Location: /index.php');
        }
    }

    static function getUserById($id) {
        $query = App::getApp()->getDB()->prepare('SELECT * FROM PERSONNE WHERE id_p = :id_p');
        $query->execute(array(':id_p' => $id));
        $user = $query->fetch();

        if($user){
            if (isset($user['salaire'])) {
                $userObj = new Instructor($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp'], $user['salaire'], $user['experience']);
            } else {
                $userObj = new User($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp']);
            }
        } else {
            $userObj = null;
        }

        return $userObj;
    }

    static function getUserByEmail($email) {
        $query = App::getApp()->getDB()->prepare('SELECT * FROM PERSONNE WHERE email = :email');
        $query->execute(array(':email' => $email));
        $user = $query->fetch();

        if($user){
            if (isset($user['salaire'])) {
                $userObj = new Instructor($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp'], $user['salaire'], $user['experience']);
            } else {
                $userObj = new User($user['id_p'], $user['nom'], $user['prenom'], $user['adresse'], $user['email'], $user['telephone'], $user['niveau'], $user['poids'], $user['mdp']);
            }
        } else {
            $userObj = null;
        }

        return $userObj;
    }

}
?>