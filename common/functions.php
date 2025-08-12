<?php

function router()
{
    return isset($_GET['page']) && match ($_GET['page']) {
        "register" => include('app/auth/register/form.php'),
        "register-treatment" => include('app/auth/register/treatment.php'),
        "login" => include('app/auth/login/form.php'),
        "login-treatment" => include('app/auth/login/treatment.php'),
        "forgot-password" => include('app/auth/forgot-password/form.php'),


        default => include('app/auth/login/form.php')
    };
}

function dbConnexion()
{
    try {
        return new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Une erreur est survenue lors de la connexion à la base de données. Détails : ' . $e->getMessage());
    }
}

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

function oldinputs(array $inputs, string $key)
{
    return !empty($inputs[$key]) ? $inputs[$key] : null;
}

function errors(array $errors, string $key)
{
    return !empty($errors[$key]) ? "<div><span class='text-danger'>" . $errors[$key] . "</span></div>" : '';
}
