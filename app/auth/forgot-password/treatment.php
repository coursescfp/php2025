<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

$_POST = sanitize($_POST);

if (empty($_POST['email'])  || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors']['email'] = 'Le champs adresse email est requis et doit contenir une adresse email valide';
}

if (is_mail_exist($_POST['email'])) {
    if (mailSendin($_POST['email'], $_POST['email'], 'Mot de passe oublié', 'La demande de changement de votre mot de passe a été bien initialisé')) {
        $_SESSION['global_success'] = 'Un mail vient d\'être envoyé à votre adresse email';
    } else {
        $_SESSION['global_error'] = 'Une erreur s\'est produite lors de l\'envoi de l\'email';
    }
} else {
    $_SESSION['errors']['email'] = 'Aucun utilisateur n\'a été trouvé avec cette adresse email';
}

if (!empty($_SESSION['errors'])) {
    $_SESSION['data'] = $_POST;
    $_SESSION['global_error'] = "Des erreurs sont survenues.";
}

redirect_to('forgot-password');