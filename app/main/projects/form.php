<?php

/**
 * Formulaire dynamique d'ajout et de modification. Pour l'ajout ou la modification, les mêmes champs reviennent 
 * dans le formulaire donc il a été préférable de le garder unique mais dynamique pour ne pas se répéter.
 * Donc suivant la valeur du paramètre page (add-project ou edit-project), nous ajustons le formulaire. Dans le
 * cas d'un ajout, il apparait vide mais pour une modification, il apparait avec les informations existantes du
 * projet à modifier et dont l'id a été passé dans l'url. Donc plusieurs contrôle s'observeront dans le
 * formulaire pour dynamiser le rendu. 
 */

$data = $_SESSION['data'] ?? []; // variable pour stocker les données soumises après traitement.

$errors = $_SESSION['errors'] ?? []; // variable pour stocker les erreurs survenues après traitement.

/**
 * Si la page demandée est 'edit-project', essayez de récupérer le projet à modifier
 */
if ($_GET['page'] == 'edit-project') {
    $project = fetch_projects(['user_id' => $_SESSION['user_connected']['id'], 'project_id' => !empty($_GET['id']) ? $_GET['id'] : null]) ?? [];

    /**
     * Si aucun projet n'est trouvé pour l'utilisateur connecté et l'id de projet soumis via l'url, 
     * alors retourner l'utilisateur vers la page d'accueil avec un message d'erreur.
     */
    if (empty($project)) {
        $_SESSION['global_error'] = 'Projet inconnu';
        redirect_to('home');
    }
}

?>

<div>
    <!--
    Le titre du formulaire changera selon la page demandée. Si page d'ajout de projet, la variable $project n'existera pas
    et donc le titre sera 'Nouveau projet', si page de modification de projet, la variable $project existera et sera non
    vide donc le titre sera 'Modifier projet {nom_du_projet}'
    -->
    <h3 class="text-primary text-center mb-5"><?= !empty($project) ? 'Modifier projet "' . $project['name'] . '"' : 'Nouveau projet' ?></h3>

    <!--
    L'attribut action contiendra une valeur de redirection vers le bon fichier de traitement selon qu'il s'agisse d'un
    ajout ou d'une modification. Le contrôle reste le même que précédemment.
    -->
    <form action="<?= !empty($project) ? '?page=edit-project-treatment&id=' . $_GET['id'] : '?page=add-project-treatment' ?>" method="post" enctype="multipart/form-data">

        <!--
        Le champ value est prérempli dans les cas suivants : soit des données ont été soumises et une erreur est survenue.
        Dans ce cas, les données soumises sont ramenées dans $data. soit nous sommes dans un cas de modification et 
        $project contient le projet à modifier.
        Cela vaut pour tous les champs.
        -->
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Nom
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="" value="<?= !empty($data) ? oldinputs($data, 'name') : (!empty($project['name']) ? $project['name'] : null) ?>" class="form-control" required>

                <?= errors($errors, 'name') ?>
            </div>
            <div class="col">
                <label for="" class="form-label">Courte description
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="short_description" id="" value="<?= !empty($data) ? oldinputs($data, 'short_description') : (!empty($project['short_description']) ? $project['short_description'] : null) ?>" class="form-control" required>

                <?= errors($errors, 'short_description') ?>
            </div>
        </div>
        <div class="row mb-3">
            <label for="" class="form-label">Description
                <span class="text-danger">*</span>
            </label>
            <div class="col">
                <textarea class="form-control" name="description" id="" cols="20" rows="5">
                    <?= !empty($data) ? oldinputs($data, 'description') : (!empty($project['description']) ? $project['description'] : null) ?>
                </textarea>
                <?= errors($errors, 'description') ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="" class="form-label">Image (Extensions autorisées : png, jpg, jpeg, gif | Poids max : 1mo)
                    <!--
                    Le champs image est requis seulement dans le cas d'un nouveau projet                
                    -->
                    <?php
                    if (empty($project)) {
                    ?>
                        <span class="text-danger">*</span>
                    <?php
                    }
                    ?>
                </label>
                <input type="file" name="image" id="" class="form-control" <?= empty($project) ? 'required' : '' ?>>

                <?= errors($errors, 'image') ?>
            </div>
        </div>

        <!--
        Ce bloc présente un aperçu de l'image existante pour le projet dans le cas d'une modification              
        -->
        <?php
        if (!empty($project)) {
        ?>
            <div class="d-flex justify-content-center">
                <img src="public/img/uploads/<?= $project['image'] ?>" class="w-25">
            </div>
        <?php
        }
        ?>

        <!--
        Le titre du bouton de soumission changera selon qu'il s'agisse d'une modification ou d'un ajout.
        -->
        <div class="mt-5 d-flex justify-content-center">
            <button type="submit" class="btn w-50 btn-primary"><?= !empty($project) ? 'Modifier' : 'Ajouter' ?></button>
        </div>
    </form>
</div>

<!--
Vider les valeurs de sessions suivantes lors du rechargement de la page
-->
<?php
    unset($_SESSION['global_error'], $_SESSION['global_success'], $_SESSION['data'], $_SESSION['errors']);
?>