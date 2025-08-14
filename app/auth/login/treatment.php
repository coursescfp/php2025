<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

if (empty($_POST['email'])) {
    $_SESSION['errors']['email'] = 'Le champs adresse email est requis';
}

if (empty($_POST['password'])) {
    $_SESSION['errors']['password'] = 'Le champs mot de passe est requis';
}



foreach ($_POST as $key => $value) {
    $_POST[$key] = htmlspecialchars($_POST[$key]);
}

$_SESSION['data'] = $_POST;


if (!empty($_SESSION['errors'])) {
    $_SESSION['global_error'] = "Des erreurs sont survenues.";

    header('location: /?page=login');
    exit;
} else {

    $_POST['password'] = sha1($_POST['password']);

    if (login($_POST)) {
        $_SESSION['global_success'] = 'Connexion effectuée avec succès.';
        header('location: /?page=home');
    } else {
        $_SESSION['global_error'] = "Adresse email ou mot de passe incorrect.";
        header('location: /?page=login');
    }
}
