<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

if (empty($_POST['last_name']) or empty($_POST['first_name']) or empty($_POST['gender']) or empty($_POST['email']) or 
empty($_POST['password']) or empty($_POST['confirm_password'])) {
    $_SESSION['global_error'] = "Tous les champs sont requis";
} 

if (!in_array($_POST['gender'], ['M','F','A'])) {
    $_SESSION['errors']['gender'] = 'Valeur du champs sexe incorrect.';
} 

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors']['email'] = 'Merci de renseigner une adresse email valide.';
}

if (is_mail_exist($_POST['email'])) {
    $_SESSION['errors']['email'] = 'Cette adresse email est déjà associé à un compte utilisateur.';
} 

if (strlen($_POST['password']) < 8) {
    $_SESSION['errors']['password'] = 'Le champs Mot de passe doit comporter minimum 8 caractères';
} 

if ($_POST['confirm_password'] !== $_POST['password']) {
    $_SESSION['errors']['confirm_password'] = 'Le champs Confirmation mot de passe et le champs Mot de passe doivent comporter les mêmes valeurs';
} 

if (empty($_POST['approve'])) {
    $_SESSION['errors']['approve'] = 'Vous devez cocher cette case avant de vous inscrire';
}

if (empty($_SESSION['global_error']) and !empty($_SESSION['errors'])) {
    $_SESSION['global_error'] = "Des erreurs sont survenues. Merci de vérifier les champs du formulaire.";
}

if (!empty($_SESSION['global_error']) or !empty($_SESSION['errors'])) {
    
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($_POST[$key]);
    }

    $_SESSION['data'] = $_POST;

    header('location: /?page=register');
    exit;
}

$_POST['password'] = sha1($_POST['password']);

unset($_POST['confirm_password'], $_POST['approve']);

if (register($_POST)) {
    $_SESSION['global_success'] = 'Inscription effectuée avec succès. Vous pouvez vous connecter';
    header('location: /?page=login');
}
