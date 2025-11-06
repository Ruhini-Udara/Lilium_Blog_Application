<?php
require 'config.php'; //database connection
redirect_if_logged_in();

//initializing variables to store user input and possible error messages
$username = '';
$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Retrieves data from the submitted form
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (!$username || !$email || !$password) {
        $error = "All fields are required!";
    } else {
        // Check if a user with the same email or username already exists
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "Username or email already taken";
        } else {
            // Hash password and insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            header('Location: login.php'); //Redirects the user to login.php after successful registration.
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>

<!-- Link to external CSS file -->
<link rel="stylesheet" href="register.css">

</head>
<body>

<div class="register-container">
    <h2>Create Account</h2>
    <?php if (!empty($error)) echo "<div class='error-message'>$error</div>"; ?>

    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="off"><br>
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="off"><br>
        
        <!-- Password field with toggle -->
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password" autocomplete="new-password"><br>
            <span id="togglePassword">show</span>
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<script>
// Show/hide password
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

