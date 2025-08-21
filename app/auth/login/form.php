<?php
/**
 * Si utilisateur déjà connecté, retourner vers la page d'acceuil
 */
if (!empty($_SESSION['user_connected'])) {
    redirect_to('home');
}

$data = $_SESSION['data'] ?? []; // variable pour stocker les données soumises après traitement.

$errors = $_SESSION['errors'] ?? []; // variable pour stocker les erreurs survenues après traitement.

?>

<div class="w-50 mx-auto border border-1 p-4 rounded rounded-4">
    <h3 class="text-primary text-center mb-5">Connexion</h3>

    <form action="?page=login-treatment" method="post">

        <!--
        Le champ value est prérempli lorsque des données ont été soumises et une erreur est survenue.
        Dans ce cas, les données soumises sont ramenées dans $data.
        Cela vaut pour tous les champs.
        -->
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
        
        <div class="text-center mt-3"><a href="?page=forgot-password">Mot de passe oublié ?</a></div>
    </form>
</div>

<!--
Vider les valeurs de sessions suivantes lors du rechargement de la page
-->
<?php
unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>