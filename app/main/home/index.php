<?php

if (empty($_SESSION['user_connected'])) {
    redirect_to('login');
}

$projects = fetch_project($_SESSION['user_connected']['id']) ?? [];

$title = '';

if (!empty($_SESSION['user_connected'])) {
    if ($_SESSION['user_connected']['gender'] == "F") {
        $title = 'Mme/Mlle';
    } elseif ($_SESSION['user_connected']['gender'] == "M") {
        $title = 'M.';
    }
}

?>

<div class="row">

    <div class="d-flex justify-content-end">
        <a href="?page=add-project" class="btn btn-primary">Ajouter</a>
    </div>

    <?php
    if (!empty($projects)) {
        foreach ($projects as $project) {
    ?>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img src="public/img/uploads/<?= $project['image'] ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $project['name'] ?></h5>
                        <p class="card-text"><?= $project['short_description'] ?></p>
                        <a href="#" class="btn btn-primary">Voir plus</a>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success']);
?>