<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();


function is_logged_in(): bool {
    return isset($_SESSION['user_id']) && $_SESSION['user_id'] !== '';
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: /auth/login.php');
        exit();
    }
}

function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function require_admin(): void {
    require_login();
    if (!is_admin()) {
        header('Location: /dashboard/user.php');
        exit();
    }
}
?>
