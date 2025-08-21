<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$_SESSION['errors'] = [];

$_SESSION['data'] = [];

$project = fetch_projects(['user_id' => $_SESSION['user_connected']['id'], 'project_id' => !empty($_GET['id']) ? $_GET['id'] : null]) ?? [];

if (empty($project)) {
    $_SESSION['global_error'] = 'Projet inconnu ou action utilisateur inattendue.';
    redirect_to('home');
}

$_POST = sanitize($_POST);

if (empty($_POST['name']) or empty($_POST['short_description']) or empty($_POST['description'])) {
    $_SESSION['global_error'] = "Tous les champs sont requis";
}

$extensions = ['png', 'jpg', 'jpeg', 'gif'];

$_POST['image'] = '';

if (!empty($_FILES['image']['size'])) {
    $fileInfo = pathinfo($_FILES['image']['name']);

    if ($_FILES['image']['error'] != 0) {
        $_SESSION['errors']['image'] = 'Une erreur est survenue lors de la soumission de votre fichier';
    } elseif ($_FILES['image']['size'] > 1000000) {
        $_SESSION['errors']['image'] = 'Taille de fichier supérieur';
    } elseif (!in_array(strtolower($fileInfo['extension']), $extensions)) {
        $_SESSION['errors']['image'] = 'Mauvaise extension';
    }

    $user = $_SESSION['user_connected']['last_name'] . '_' . $_SESSION['user_connected']['first_name'] . '_' . $_SESSION['user_connected']['id'];

    $path = dirname(__DIR__, 4) . '/public/img/uploads/' . $user . '/' . $_POST['name'] . '/';

    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    $newName = substr(str_shuffle(md5($fileInfo['filename'])), 0, 7);

    $_POST['image'] =  $user . '/' . $_POST['name'] . '/' . $newName . '.' . $fileInfo['extension'];

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $path . $newName . '.' . $fileInfo['extension'])) {
        $_SESSION['global_error'] = 'Une erreur s\'est produite lors du processus.';
        return_back($_POST);
    }

} else {
    $_POST['image'] = $project['image'];
}

if (empty($_SESSION['global_error']) and !empty($_SESSION['errors'])) {
    $_SESSION['global_error'] = "Des erreurs sont survenues. Merci de vérifier les champs du formulaire.";
}

if (!empty($_SESSION['global_error']) or !empty($_SESSION['errors'])) {
    return_back($_POST);
}

$_POST['project_id'] = !empty($_GET['id']) ? $_GET['id'] : null;
$_POST['updated_at'] = now();

if (update_project($_POST)) {
    $_SESSION['global_success'] = 'Projet mis à jour avec succès';
    redirect_to('home');
} else {
    $_SESSION['global_error'] = 'Une erreur s\'est produite lors de la mise à jour du projet';
    return_back($_POST);
}

function return_back($data)
{
   $_SESSION['data'] = $data;

    redirect_to('edit-project', !empty($_GET['id']) ? $_GET['id'] : null);
}
