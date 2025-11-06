<?php
require 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$blog_id = $_GET['id'] ?? null;

if (!$blog_id) {
    header('Location: profile.php');
    exit;
}

// Delete only if the blog belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM blogpost WHERE id = ? AND user_id = ?");
$stmt->execute([$blog_id, $user_id]);

// Redirect with success message
header('Location: profile.php?deleted=1');
exit;

