<?php

session_start();

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

include_once('common/functions.php');

?>

<!DOCTYPE html>
<html lang="fr">

<!-- head -->
<?php include_once('common/head.php') ?>

<body class="index-page">

    <!-- header -->
    <?php include_once('common/header.php') ?>

    <main class="main py-5 _main">

        <section class="container content">

            <?php
            if (!empty($_SESSION['global_error'])) {
            ?>

                <div class="alert alert-danger alert-dismissible fade show w-50 mx-auto"><?= $_SESSION['global_error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            <?php
            }
            ?>

            <?php
            if (!empty($_SESSION['global_success'])) {
            ?>

                <div class="alert alert-success alert-dismissible fade show w-50 mx-auto"><?= $_SESSION['global_success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

            <?php
            }
            ?>

            <?php router() ?>

        </section>

    </main>

    <!-- footer -->
    <?php include_once('common/footer.php') ?>

    <!-- foot -->
    <?php include_once('common/foot.php') ?>

</body>

</html>