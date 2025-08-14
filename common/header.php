<?php ob_start() ?>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="index.html" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <h1 class="sitename">Portfolio-CFP</h1>
        </a>

        <?php
        if (!empty($_SESSION['user_connected'])) {
        ?>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#" class="<?= isset($_GET['page']) && $_GET['page'] == 'home' ? 'active' : '' ?>">Accueil<br></a></li>

                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn btn-danger flex-md-shrink-0 ms-3" href="?page=logout">Déconnexion</a>
        <?php
        } else {
        ?>

            <a class="btn-getstarted flex-md-shrink-0" href="?page=login">Connexion</a>
            <a class="btn-getstarted flex-md-shrink-0" href="?page=register">Inscription</a>
        <?php
        }
        ?>

    </div>
</header>