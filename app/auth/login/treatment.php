<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

$_POST = sanitize($_POST);

if (empty($_POST['email'])) {
    $_SESSION['errors']['email'] = 'Le champs adresse email est requis';
}

if (empty($_POST['password'])) {
    $_SESSION['errors']['password'] = 'Le champs mot de passe est requis';
}

$_SESSION['data'] = $_POST;

if (!empty($_SESSION['errors'])) {
    $_SESSION['global_error'] = "Des erreurs sont survenues.";

    redirect_to('login');
} else {

    $_POST['password'] = sha1($_POST['password']);

    if (login($_POST)) {
        $_SESSION['global_success'] = 'Connexion effectuée avec succès.';
        redirect_to('home');
    } else {
        $_SESSION['global_error'] = "Adresse email ou mot de passe incorrect.";
        redirect_to('login');
    }
}
