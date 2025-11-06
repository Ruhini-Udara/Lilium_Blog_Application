<?php
require 'config.php';
require_login();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get current user ID
$user_id = $_SESSION['user_id'];

// Check if bio is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $bio = trim($_POST['bio']);

    // Optional: limit bio length
    if (strlen($bio) > 500) {
        $bio = substr($bio, 0, 500);
    }

    // Update bio in database
    $stmt = $pdo->prepare("UPDATE user SET bio = ? WHERE id = ?");
    $stmt->execute([$bio, $user_id]);
}

// Redirect back to profile
header('Location: profile.php');
exit;
