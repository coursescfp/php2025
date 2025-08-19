<?php

if (!empty($_SESSION['user_connected'])) {
    redirect_to('home');
}

$data = $_SESSION['data'] ?? [];

$errors = $_SESSION['errors'] ?? [];

?>


<div>
    <h3 class="text-primary text-center mb-5">Inscription</h3>

    <form action="?page=register-treatment" method="post">
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Nom
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="last_name" id="" value="<?= !empty($data) ? oldinputs($data, 'last_name') : '' ?>" class="form-control" required>

                <?= errors($errors, 'last_name') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Prénoms
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="first_name" id="" value="<?= !empty($data) ? oldinputs($data, 'first_name') : '' ?>" class="form-control" required>
                
                <?= errors($errors, 'first_name') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Sexe
                    <span class="text-danger">*</span>
                </label>
                <select name="gender" id="" class="form-select" required>
                    <option <?= (!empty($data['gender']) && $data['gender'] == "M") ? "selected" : '' ?> value="M">Masculin</option>
                    <option <?= (!empty($data['gender']) && $data['gender'] == "F") ? "selected" : '' ?> value="F">Féminin</option>
                    <option <?= (!empty($data['gender']) && $data['gender'] == "A") ? "selected" : '' ?> value="A">Non précisé</option>
                </select>
                
                <?= errors($errors, 'gender') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" name="email" id="" value="<?= !empty($data) ? oldinputs($data, 'email') : '' ?>" class="form-control" required>
                
                <?= errors($errors, 'email') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Mot de passe
                    <span class="text-danger">*</span>
                </label>
                <input type="password" name="password" id="" class="form-control" required>
                
                <?= errors($errors, 'password') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Confirmation mot de passe
                    <span class="text-danger">*</span>
                </label>
                <input type="password" name="confirm_password" id="" class="form-control" required>

                <?= errors($errors, 'confirm_password') ?>
            </div>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="approve" name="approve" <?= !empty($data['approve']) ? "checked" : '' ?>>
            <label class="form-check-label" for="approve">
                J'accepte les termes et conditions
                <span class="text-danger">*</span>
            </label>

            <?= errors($errors, 'approve') ?>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary">Je m'inscris</button>
        </div>
    </form>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>