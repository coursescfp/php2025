<?php

if (!empty($_SESSION['user_connected'])) {
    session_destroy();
    $_SESSION['global_success'] = 'vous êtes déconnecté.';

    redirect_to('login');
}