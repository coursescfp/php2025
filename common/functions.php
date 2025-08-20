<?php

/**
 * Fonction de redirection
 */
function redirect_to($page)
{
    header('location: /?page=' . $page);
    exit;
}

/**
 * Fonction pour retourner le moment actuel (date et heure au format souhaité ou 'AAAA-MM-JJ H:m:s' par défaut)
 */
function now(string|null $format=null)
{
    return date($format ?? 'Y-m-d H:i:s');
}

/**
 * Fonction pour ramener les anciennes données soumises en cas d'erreurs
 */
function oldinputs(array $inputs, string $key)
{
    return !empty($inputs) && !empty($inputs[$key]) ? $inputs[$key] : null;
}

/**
 * Fonction pour afficher un message d'erreur spécifique à chaque champ en cas d'erreur
 */
function errors(array $errors, string $key)
{
    return !empty($errors[$key]) ? "<div><span class='text-danger'>" . $errors[$key] . "</span></div>" : '';
}

/**
 * Fonction permettant de retourner la ressource demandée via la variable $GET['page']
 */

function router()
{
    return isset($_GET['page']) ? 
    match ($_GET['page']) {
        "register" => require('app/auth/register/form.php'),
        "register-treatment" => require('app/auth/register/treatment.php'),
        "login" => require('app/auth/login/form.php'),
        "login-treatment" => require('app/auth/login/treatment.php'),
        "forgot-password" => require('app/auth/forgot-password/form.php'),

        "home" => require('app/main/home/index.php'),
        "add-project" => require('app/main/projects/form.php'),
        "add-project-treatment" => require('app/main/projects/create/treatment.php'),
        "edit-project" => require('app/main/projects/form.php'),
        "edit-project-treatment" => require('app/main/projects/edit/treatment.php'),
        "delete-project" => require('app/main/projects/delete.php'),

        "logout" => require('app/auth/logout.php'),


        default => require('app/auth/login/form.php')
    } : 
    require('app/auth/login/form.php');
}

/**
 * Fonction pour la connexion à la base de données
 */
function dbConnection()
{
    try {
        return new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (Exception $e) {
        die('Une erreur est survenue lors de la connexion à la base de données. Détails : ' . $e->getMessage());
    }
}

/**
 * Fonction d'inscription utilisateur
 */
function register(array $data)
{
    $registered = false;

    $pdoInstance = dbConnection();

    $request = 'INSERT INTO users (last_name, first_name, gender, email, password) VALUES (:last_name, :first_name, :gender, :email, :password)';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute($data);

    if ($execution) {
        $registered = true;
    }

    return $registered;
}

/**
 * Fonction pour vérifier si une adresse email essayant de s'inscrire existe déjà en base de données dans la 
 * table utilisateur
 */
function is_mail_exist(string $email)
{
    $existed = false;

    $data = [];

    $pdoInstance = dbConnection();

    $request = 'SELECT * FROM users where email=:email';

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute(['email' => $email]);

    $data = $preparation->fetch();

    if (!empty($data)) {
        $existed = true;
    }

    return $existed;
}

/**
 * Fonction de connexion
 */
function login(array $data)
{
    $logged = false;

    $pdoInstance = dbConnection();

    $request = 'SELECT id, last_name, first_name, gender, email, avatar FROM users where email=:email and password=:password';

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute($data);

    $_SESSION['user_connected'] = $preparation->fetch();

    if (is_array($_SESSION['user_connected'])) {
        $logged = true;
    }

    return $logged;
}

/**
 * Fonction d'enregistrement d'un projet
 */
function insert_project(array $data)
{
    $inserted = false;

    $pdoInstance = dbConnection();

    $request = 'INSERT INTO projects (name, short_description, description, image, user_id) VALUES (:name, :short_description, :description, :image, :user_id)';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute($data);

    if ($execution) {
        $inserted = true;
    }

    return $inserted;
}

/**
 * Fonction de récupération de projet
 */
function fetch_projects(array $fetchRequestData)
{
    $data = [];

    $pdoInstance = dbConnection();

    $request = 'SELECT * FROM projects WHERE user_id=:user_id';

    if (!empty($fetchRequestData['project_id'])) {
        $request .= ' AND id=:project_id';
    }

    $request .= ' AND deleted_at IS NULL ORDER BY id DESC';

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute($fetchRequestData);

    $data = $preparation->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

/**
 * Fonction de mise à jour d'un projet
 */
function update_project(array $data)
{
    $updated = false;

    $pdoInstance = dbConnection();

    $request = 'UPDATE projects SET name=:name, short_description=:short_description, description=:description, image=:image, updated_at=:updated_at WHERE id=:project_id';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute($data);

    if ($execution) {
        $updated = true;
    }

    return $updated;
}

/**
 * Fonction de suppression d'un projet
 */
function delete_project(array $data)
{
    $deleted = false;

    $pdoInstance = dbConnection();

    $request = 'UPDATE projects SET deleted_at=:deleted_at WHERE id=:project_id';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute($data);

    if ($execution) {
        $deleted = true;
    }

    return $deleted;
}

/**
 * Fonction pour vérifier l'existence d'un projet pour un utilisateur
 */
function check_user_project(array $checkingRequestData)
{
    $existed = false;

    $data = [];

    $pdoInstance = dbConnection();

    $request = 'SELECT * FROM projects where id=:project_id AND user_id=:user_id';

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute($checkingRequestData);

    $data = $preparation->fetch();

    if (!empty($data)) {
        $existed = true;
    }

    return $existed;
}