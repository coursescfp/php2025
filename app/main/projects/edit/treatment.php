<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

if (
    empty($_POST['name']) or empty($_POST['short_description']) or empty($_POST['description'])
    or !isset($_FILES['image'])
) {
    $_SESSION['global_error'] = "Tous les champs sont requis";
}

$extensions = ['png', 'jpg', 'jpeg', 'gif'];

if (isset($_FILES['image'])) {
    $fileInfo = pathinfo($_FILES['image']['name']);

    if ($_FILES['image']['error'] != 0) {
        $_SESSION['errors']['image'] = 'Une erreur est survenue lors de la soumission de votre fichier';
    } elseif ($_FILES['image']['size'] > 1000000) {
        $_SESSION['errors']['image'] = 'Taille de fichier supérieur';
    } elseif (!in_array(strtolower($fileInfo['extension']), $extensions)) {
        $_SESSION['errors']['image'] = 'Mauvaise extension';
    }
}

if (empty($_SESSION['global_error']) and !empty($_SESSION['errors'])) {
    $_SESSION['global_error'] = "Des erreurs sont survenues. Merci de vérifier les champs du formulaire.";
}

if (!empty($_SESSION['global_error']) or !empty($_SESSION['errors'])) {
    return_back($_POST);
}

$user = $_SESSION['user_connected']['last_name'] . '_' . $_SESSION['user_connected']['first_name'] . '_' . $_SESSION['user_connected']['id'];

$path = dirname(__DIR__, 4) . '/public/img/uploads/' . $user . '/' . $_POST['name'] . '/';

if (!is_dir($path)) {
    mkdir($path, 0755, true);
}

$newName = substr(str_shuffle(md5($fileInfo['filename'])), 0, 7);

$imagePath =  $user . '/' . $_POST['name'] . '/' . $newName . '.' . $fileInfo['extension'];

//die(var_dump($path));

if (move_uploaded_file($_FILES['image']['tmp_name'], $path . $newName . '.' . $fileInfo['extension'])) {

    $_POST['image'] = $imagePath;
    $_POST['updated_at'] = now();

    if (update_project($_POST)) {
        $_SESSION['global_success'] = 'Projet mis à jour avec succès';
        redirect_to('home');
    }
} else {
    $_SESSION['global_error'] = 'Une erreur s\'est produite lors de la mise à jour du projet';
    return_back($_POST);
}



function return_back($data) {
    foreach ($data as $key => $value) {
        $data[$key] = htmlspecialchars($value);
    }

    $_SESSION['data'] = $data;

    redirect_to('edit-project');
}