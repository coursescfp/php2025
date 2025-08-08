<?php
session_start();

include_once('common/functions.php');


?>

<!DOCTYPE html>
<html lang="fr">

<!-- head -->
<?php include_once('common/head.php') ?>

<body class="index-page">

    <!-- header -->
    <?php include_once('common/header.php') ?>

    <main class="main py-5">

        <section class="container">
            <?php router() ?>
        </section>

    </main>

    <!-- footer -->
    <?php include_once('common/footer.php') ?>

    <!-- foot -->
    <?php include_once('common/foot.php') ?>

</body>

</html>