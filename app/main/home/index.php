<?php

if (empty($_SESSION['user_connected'])) {
    redirect_to('login');
}

$count_projects = count_projects($_SESSION['user_connected']['id']); // nombre de projets en tout pour l'utilisateur connecté
$per_page = 10; // nombre de projets à afficher par page
$page = !empty($_GET['page_number']) ? $_GET['page_number'] : 1; // page actuelle

/**
 * Récupérer les projets de l'utilisateur connecté avec pagination.
 */
$projects = fetch_projects(['user_id' => $_SESSION['user_connected']['id'], 'per_page' => $per_page, 'page' => $page]) ?? [];

?>

<div class="row gx-5">

    <div class="d-flex justify-content-end mb-5">
        <a href="?page=add-project" class="btn btn-primary">Ajouter</a>
    </div>

    <?php
    if (!empty($projects)) {
        foreach ($projects as $project) {
    ?>
            <div class="col-md-3 g-3">
                <div class="card h-100" style="width: 18rem;">
                    <img src="public/img/uploads/<?= $project['image'] ?>" data-bs-toggle="modal" data-bs-target="#project_detail<?= $project["id"] ?>" class="card-img-top h-100" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $project['name'] ?></h5>
                        <p class="card-text"><?= $project['short_description'] ?></p>
                        <div class="row g-2">
                            <div class="col">
                                <a href="?page=edit-project&id=<?= $project['id'] ?>" class="btn btn-warning">Modifier</a>
                            </div>
                            <div class="col">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#delete_project<?= $project["id"] ?>" class="btn btn-danger">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal d'aperçu -->
            <div class="modal fade" id="project_detail<?= $project['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5"><?= $project['name'] ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="p-1 w-100">
                                <img src="public/img/uploads/<?= $project['image'] ?>" class="w-100"
                                    alt="Image du projet">
                            </div>

                            <p class="p-1 m-0 text-sm text-muted"><em
                                    class="text-justify"><?= $project['description'] ?></em></p>
                            <hr class="m-0 mx-1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de suppression -->
            <div class="modal fade" id="delete_project<?= $project['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Supprimer <?= $project['name'] ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Êtes-vous sûr(e) de vouloir supprimer ce projet ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                            <a href="?page=delete-project&id=<?= $project['id'] ?>" class="btn btn-primary">Oui,
                                supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        }
    }
    ?>

    <!-- Pagination -->
    <div class="d-flex justify-content-center p-5">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link <?= $page == 1 ? 'disabled' : '' ?>" href="?page=home&page_number=<?= !empty($page) && $page > 1 ? $page - 1 : 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link active" href="#"><?= $page ?></a></li>
                <li class="page-item">
                    <a class="page-link <?= $count_projects - ($page * $per_page) <= 0 ? 'disabled' : '' ?>" href="?page=home&page_number=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!--
Vider les valeurs de sessions suivantes lors du rechargement de la page
-->
<?php
unset($_SESSION['global_error'], $_SESSION['global_success']);
?>