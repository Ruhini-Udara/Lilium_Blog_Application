<?php
require 'config.php';
redirect_if_logged_in();

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember_me']);

    if (!$email || !$password) {
        $error = "All fields are required";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            if ($remember) {
                set_remember_me_cookie($user['id'], $user['username']);
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post" autocomplete="off" autocapitalize="off" spellcheck="false">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required><br>

        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password" autocomplete="new-password" required><br>
            <span id="togglePassword">show</span>
        </div>

        <!-- Remember Me -->
        <div class="remember-me">
            <input type="checkbox" id="remember_me" name="remember_me">
            <label for="remember_me">Remember Me</label>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="login-link">
        Don't have an account? <a href="register.php">Sign up</a>
    </div>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');
togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? 'show' : 'hide';
});
</script>
</body>
</html>

