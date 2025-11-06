<?php
// ---------------------------------------------
// SESSION & SECURITY
// ---------------------------------------------
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// ---------------------------------------------
// DATABASE CONNECTION
// ---------------------------------------------
$db_host = 'localhost';
$db_name = 'blog_app';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ---------------------------------------------
// HELPER FUNCTIONS
// ---------------------------------------------

function current_user() {
    // Check session first
    if (isset($_SESSION['user_id'])) {
        return ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']];
    }

    // Check Remember Me cookie
    if (isset($_COOKIE['remember_me'])) {
        global $pdo;
        $token = $_COOKIE['remember_me'];
        $hashed = hash('sha256', $token);

        $stmt = $pdo->prepare("SELECT id, username FROM user WHERE remember_token = ?");
        $stmt->execute([$hashed]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Restore session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return $user;
        } else {
            // Invalid token → clear cookie
            setcookie('remember_me', '', time() - 3600, '/');
        }
    }

    return null;
}

function require_login() {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function redirect_if_logged_in() {
    if (current_user()) {
        header('Location: dashboard.php');
        exit;
    }
}

// Set Remember Me cookie (secure version)
function set_remember_me_cookie($user_id, $username) {
    global $pdo;

    // Create a random token and hash it
    $token = bin2hex(random_bytes(32));
    $hashedToken = hash('sha256', $token);

    // Save hashed token in DB
    $stmt = $pdo->prepare("UPDATE user SET remember_token = ? WHERE id = ?");
    $stmt->execute([$hashedToken, $user_id]);

    // Set cookie for 30 days (HttpOnly)
    setcookie('remember_me', $token, time() + 86400 * 30, '/', '', false, true);
}
?>

