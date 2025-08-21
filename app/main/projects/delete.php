<?php

$_SESSION['global_error'] = "";

$_SESSION['global_success'] = "";

$project = fetch_projects(['user_id' => $_SESSION['user_connected']['id'], 'project_id' => !empty($_GET['id']) ? $_GET['id'] : null]) ?? [];

if (empty($project)) {
    $_SESSION['global_error'] = 'Projet inconnu ou action utilisateur inattendue.';
    redirect_to('home');
}

$data = [];

$data['deleted_at'] = now();
$data['project_id'] = !empty($_GET['id']) ? $_GET['id'] : null;

$dirToRemove = dirname(__DIR__, 3) . '/public/img/uploads/' . $_SESSION['user_connected']['last_name'] . '_' . 
$_SESSION['user_connected']['first_name'] . '_' . $_SESSION['user_connected']['id'] . '/' . $project['name'];

if (delete_dir($dirToRemove) && delete_project($data)) {
    $_SESSION['global_success'] = "Projet {$project['name']} supprimé avec succès";
} else {
    $_SESSION['global_error'] = 'Une erreur s\'est produite lors de la suppression du projet ' . $project['name'];
}

redirect_to('home');