<?php

require_once 'db.php';

session_start();

function isUserLoggedIn(){
    return isset($_SESSION['user_id']);
}

function checkUserLoggedIn(){
    if(!isUserLoggedIn()){
        header('Location: /index.php');
    }
}

function getUserByEmail($email){
    global $bd;
    $query = $bd->prepare('SELECT * FROM users WHERE email = :email');
    $query->execute(array(':email' => $email));

    return $query->fetch();
}

function checkLoginForm($email, $password){
    $user = getUserByEmail($email);

    if($user){
        //verify password
        if(password_verify($password, $user['password'])){
            //set session

            //TODO : store user in session instead of user_id (SESSION['user']['id'])
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];

            //redirect to home page
            header('Location: /');
        }else{
            $error = 'Mot de passe incorrect';
        }
    }else{
        $error = "Nom d'utilisateur incorrect";
    }

    return $error;
}

?>