<?php

$data = $_SESSION['data'] ?? [];

$errors = $_SESSION['errors'] ?? [];

?>


<div>
    <h3 class="text-primary text-center mb-5">Nouveau projet</h3>

    <form action="?page=add-project-treatment" method="post" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Nom
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="" value="<?= !empty($data) ? oldinputs($data, 'name') : '' ?>" class="form-control" required>

                <?= errors($errors, 'name') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Courte description
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="short_description" id="" value="<?= !empty($data) ? oldinputs($data, 'short_description') : '' ?>" class="form-control" required>

                <?= errors($errors, 'short_description') ?>
            </div>
        </div>
        <div class="row mb-3">
            <label for="" class="form-label">Description
                <span class="text-danger">*</span>
            </label>
            <div class="col">
                <textarea class="form-control" name="description" id="" cols="20" rows="5">
                    <?= !empty($data) ? oldinputs($data, 'description') : '' ?>
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

        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary">Ajouter</button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>