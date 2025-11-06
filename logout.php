<?php
require 'config.php';

session_start();

// If "remember me" cookie exists, clear token in DB
if (isset($_COOKIE['remember_me'])) {
    $hashed = hash('sha256', $_COOKIE['remember_me']);
    $stmt = $pdo->prepare("UPDATE user SET remember_token = NULL WHERE remember_token = ?");
    $stmt->execute([$hashed]);

    // Delete cookie
    setcookie('remember_me', '', time() - 3600, '/');
}

// Destroy session
session_unset();
session_destroy();

header("Location: login.php");
exit();
?>
