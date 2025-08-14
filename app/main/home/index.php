<?php

if (empty($_SESSION['user_connected'])) {
    header('location: /?page=login');
    exit;
}

$title = '';

if(!empty($_SESSION['user_connected'])) {
    if ($_SESSION['user_connected']['gender'] == "F") {
        $title = 'Mme/Mlle';
    } elseif ($_SESSION['user_connected']['gender'] == "M") {
        $title = 'M.';
    }
}

?>

<div>
    <h1>Bienvenue sur le site <?= $title . ' ' . $_SESSION['user_connected']['last_name'] . ' ' . $_SESSION['user_connected']['first_name'] ?> </h1>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success']);
?>