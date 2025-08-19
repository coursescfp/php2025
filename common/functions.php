<?php

function redirect_to($page)
{
    define("PROJECT_NAME", 'portfolio');

    header('location: /' . PROJECT_NAME . '/?page=' . $page);
    
    exit;
}

/**
 * Fonction permettant de retourner la ressource demandée via la variable $GET['page']
 */

function router()
{
    return isset($_GET['page']) && match ($_GET['page']) {

        "register" => include('app/auth/register/form.php'),
        "register-treatment" => include('app/auth/register/treatment.php'),
        "login" => include('app/auth/login/form.php'),
        "login-treatment" => include('app/auth/login/treatment.php'),
        "forgot-password" => include('app/auth/forgot-password/form.php'),

        "home" => include('app/main/home/index.php'),
        "add-project" => include('app/main/projects/create/form.php'),
        "add-project-treatment" => include('app/main/projects/create/treatment.php'),

        "logout" => include('app/auth/logout.php'),


        default => include('app/auth/login/form.php')
    };
}

/**
 * Fonction pour la connexion à la base de données
 */
function dbConnexion()
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

    $pdoInstance = dbConnexion();

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

    $data = null;

    $pdoInstance = dbConnexion();

    $request = 'SELECT * FROM users where email=:email';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute([
        'email' => $email
    ]);

    $data = $preparation->fetch();

    if (is_array($data)) {
        $existed = true;
    }

    return $existed;
}

/**
 * Fonction pour ramener les anciennes données soumises en cas d'erreurs
 */
function oldinputs(array $inputs, string $key)
{
    return !empty($inputs[$key]) ? $inputs[$key] : null;
}

/**
 * Fonction pour afficher un message d'erreur spécifique à chaque champ en cas d'erreur
 */
function errors(array $errors, string $key)
{
    return !empty($errors[$key]) ? "<div><span class='text-danger'>" . $errors[$key] . "</span></div>" : '';
}

/**
 * Fonction de connexion
 */
function login(array $data)
{
    $logged = false;

    $pdoInstance = dbConnexion();

    $request = 'SELECT id, last_name, first_name, gender, email, avatar FROM users where email=:email and password=:password';

    $preparation = $pdoInstance->prepare($request);

    //die(var_dump($data));

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

    $pdoInstance = dbConnexion();

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
function fetch_project(int $user_id)
{
    $data = [];

    $pdoInstance = dbConnexion();

    $request = 'SELECT * FROM projects where user_id=:user_id and deleted_at IS NULL';

    $preparation = $pdoInstance->prepare($request);

    $execution = $preparation->execute(['user_id' => $user_id]);

    $data = $preparation->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}