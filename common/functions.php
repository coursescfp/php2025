<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailSendin(string $destination, string $recipient, string $subject, string $body): bool
{
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = $_ENV['MAIL_FROM_ADDRESS'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        $mail->setFrom($mail->Username, htmlspecialchars_decode($_ENV['MAIL_FROM_NAME']));
        $mail->addAddress($destination, $recipient);
        $mail->addReplyTo($mail->Username, htmlspecialchars_decode($_ENV['MAIL_FROM_NAME']));

        $mail->IsHTML(true);
        $mail->Subject = htmlspecialchars_decode($subject);
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Fonction de redirection
 */
function redirect_to($page, $id = null)
{
    $base = str_contains($_SERVER['REQUEST_URI'], 'portfolio') ? '/portfolio' : '';

    is_null($id) ? header('location: ' . $base . '/?page=' . $page) : header('location: ' . $base . '/?page=' . $page . '&id=' . $id);
    exit;
}

/**
 * Fonction pour retourner le moment actuel (date et heure au format souhaité ou 'AAAA-MM-JJ H:m:s' par défaut)
 */
function now(string|null $format = null)
{
    return date($format ?? 'Y-m-d H:i:s');
}

/**
 * Fonction pour assainir les entrées utilisateur
 */
function sanitize(array $inputs)
{
    foreach ($inputs as $key => $value) {
        $inputs[$key] = htmlspecialchars(trim($value));
    }

    return $inputs;
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
            "forgot-password-treatment" => require('app/auth/forgot-password/treatment.php'),

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
    $db_host = $_ENV['DB_HOST'];
    $db_name = $_ENV['DB_NAME'];
    $db_user = $_ENV['DB_USER'];
    $db_password = $_ENV['DB_PASSWORD'];

    try {
        return new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
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
 * Fonction de récupération du nombre total de projets pour un utilisateur
 */
function count_projects(int $user_id)
{
    $pdoInstance = dbConnection();

    $request = 'SELECT COUNT(*) FROM projects WHERE user_id=:user_id AND deleted_at IS NULL';

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute(['user_id' => $user_id]);

    return $preparation->fetch()[0];
}

/**
 * Fonction de récupération de projet, pagination incluse
 */
function fetch_projects(array $fetchRequestData)
{
    $per_page = 2;
    $page = 1;

    if (array_key_exists('per_page', $fetchRequestData)) {
        $per_page = $fetchRequestData['per_page'];
        unset($fetchRequestData['per_page']);
    }

    if (array_key_exists('page', $fetchRequestData)) {
        $page = $fetchRequestData['page'];
        unset($fetchRequestData['page']);
    }

    $offset = ($page - 1) * $per_page;

    $data = [];

    $pdoInstance = dbConnection();

    $request = 'SELECT * FROM projects WHERE user_id=:user_id';

    if (array_key_exists('project_id', $fetchRequestData)) {
        $request .= ' AND id=:project_id';
    }

    $request .= " AND deleted_at IS NULL ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";

    $preparation = $pdoInstance->prepare($request);

    $preparation->execute($fetchRequestData);

    $data = array_key_exists('project_id', $fetchRequestData) ? $preparation->fetch() : $preparation->fetchAll(PDO::FETCH_ASSOC);

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
 * Fonction de suppression d'un dossier
 */
function delete_dir($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $files = scandir($dir); // retourne les fichiers et/ou sous-dossiers contenu dans le dossier $dir
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                delete_dir($path);
            } else {
                unlink($path);
            }
        }
    }

    return rmdir($dir);
}
