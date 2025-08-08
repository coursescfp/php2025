<?php

function router() {
    return isset($_GET['page']) && match ($_GET['page']) {
        "register" => include('app/auth/register/form.php'),
        "register-treatment" => include('app/auth/register/treatment.php'),
        "login" => include('app/auth/login/form.php'),
        "login-treatment" => include('app/auth/login/treatment.php'),
        "forgot-password" => include('app/auth/forgot-password/form.php'),


        default => include('app/auth/login/form.php')
    };
}