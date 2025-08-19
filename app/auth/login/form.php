<?php

if (!empty($_SESSION['user_connected'])) {
    redirect_to('home');
}

$data = $_SESSION['data'] ?? [];

$errors = $_SESSION['errors'] ?? [];

?>

<div>
    <h3 class="text-primary text-center mb-5">Connexion</h3>

    <form action="?page=login-treatment" method="post">
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Email
                    <span class="text-danger">*</span>
                </label>
                <input type="email" name="email" id="" value="<?= !empty($data) ? oldinputs($data, 'email') : '' ?>" class="form-control" required>

                <?= errors($errors, 'email') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Mot de passe
                    <span class="text-danger">*</span>
                </label>
                <input type="password" name="password" id="" class="form-control" required>

                <?= errors($errors, 'password') ?>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary">Connexion</button>
        </div>
        
        <div><a href="?page=forgot-password">Mot de passe oublié ?</a></div>
    </form>
</div>

<?php
unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>