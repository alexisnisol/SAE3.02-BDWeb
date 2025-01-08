<?php

namespace App\Auth;

use App\Auth\Users\User;

class AuthForm {

    static function checkLoginForm($email, $password) {
        $user = Auth::getUserByEmail($email);

        if($user){
            //verify password
            if(password_verify($password, $user->password)){
                //TODO : store user in session instead of user_id (SESSION['user']['id'])
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_name'] = $user->lastName;
                header('Location: /');
            }else{
                $error = 'Mot de passe incorrect';
            }
        }else{
            $error = "Nom d'utilisateur incorrect";
        }

        return $error;
    }

    static function checkRegisterForm($email, $password, $firstName, $lastName, $address, $phone, $level, $weight) {
        $user = Auth::getUserByEmail($email);

        if(!$user){
            $userObj = new User(NULL, $firstName, $lastName, $address, $email, $phone, $level, $weight, $password);
            $userObj->register();

            //redirect to login page
            header('Location: /index.php?action=login');
        }else{
            $error = "Un utilisateur avec cet email existe déjà";
        }

        return $error;
    }
}

?>