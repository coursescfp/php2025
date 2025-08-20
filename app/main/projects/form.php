<?php

$data = $_SESSION['data'] ?? [];

$errors = $_SESSION['errors'] ?? [];

if ($_GET['page'] == 'edit-project' && !check_user_project(['user_id' => $_SESSION['user_connected']['id'], 'project_id' => !empty($_GET['id']) ? $_GET['id'] : null])) {
    $_SESSION['global_error'] = 'Projet inconnu';
    redirect_to('home');
}

$project = fetch_projects(['user_id' => $_SESSION['user_connected']['id'], 'project_id' => !empty($_GET['id']) ? $_GET['id'] : null]) ?? [];

?>

<div>
    <h3 class="text-primary text-center mb-5"><?= !empty($project) ? 'Modifier projet "' . $project[0]['name'] . '"' : 'Nouveau projet' ?></h3>

    <form action="<?= !empty($project) ? '?page=edit-project-treatment&id='.$_GET['id'] : '?page=add-project-treatment' ?>" method="post" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Nom
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="" value="<?= empty($project[0]['name']) ? oldinputs($data, 'name') : $project[0]['name'] ?>" class="form-control" required>

                <?= errors($errors, 'name') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Courte description
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="short_description" id="" value="<?= empty($project[0]['short_description']) ? oldinputs($data, 'short_description') : $project[0]['short_description'] ?>" class="form-control" required>

                <?= errors($errors, 'short_description') ?>
            </div>
        </div>
        <div class="row mb-3">
            <label for="" class="form-label">Description
                <span class="text-danger">*</span>
            </label>
            <div class="col">
                <textarea class="form-control" name="description" id="" cols="20" rows="5">
                    <?= empty($project[0]['description']) ? oldinputs($data, 'description') : $project[0]['description'] ?>
                </textarea>
                <?= errors($errors, 'description') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Image (Extensions autorisées : png, jpg, jpeg, gif | Poids max : 1mo)
                    <span class="text-danger">*</span>
                </label>
                <input type="file" name="image" id="" class="form-control" required>

                <?= errors($errors, 'image') ?>
            </div>
        </div>

        <?php
        if (!empty($project)) {
        ?>
            <div class="d-flex justify-content-center">
                <img src="public/img/uploads/<?= $project[0]['image'] ?>" class="w-25">
            </div>
        <?php
        }
        ?>

        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary"><?= !empty($project) ? 'Modifier' : 'Ajouter' ?></button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>