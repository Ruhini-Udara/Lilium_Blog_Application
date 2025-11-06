<?php
// Include configuration and helper functions
require 'config.php';

// Ensure only logged-in users can access this page
require_login();

// Include Parsedown for rendering Markdown content as HTML
require 'Parsedown.php';

// Initialize Parsedown safely (prevents XSS attacks)
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

// Get current logged-in user's username for display in topbar/profile circle
$username = $_SESSION['username'] ?? 'User';

// Get blog ID from URL
$blog_id = $_GET['id'] ?? null;
if (!$blog_id) {
    header('Location: dashboard.php');  // Redirect to dashboard if no blog ID is provided
    exit;
}

// Fetch blog post with author info
$stmt = $pdo->prepare("
    SELECT b.title, b.content, b.created_at, u.username
    FROM blogpost b
    JOIN user u ON b.user_id = u.id
    WHERE b.id = ?
");
$stmt->execute([$blog_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    echo "Blog post not found!";  // Show error if blog not found
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($blog['title']) ?> - Lilium</title>

<link rel="stylesheet" href="view_blog.css">

<script>
  // Reload if navigating with back/forward buttons to prevent stale data
  window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>

</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <nav class="sidebar-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="profile.php">👤 Profile</a>
  </nav>
  <a href="logout.php" class="sidebar-logout">Logout</a>
</aside>

<!-- Topbar -->
<header class="topbar">
  <div class="logo">Lilium</div>
  <nav class="top-nav">
    <a href="create_blog.php" class="write-link">✍🏻 Write Something...</a>
    <a href="profile.php" class="profile-circle" title="<?= htmlspecialchars($username) ?>">
      <?= strtoupper(substr($username, 0, 1)) ?>
    </a>
  </nav>
</header>

<!-- Blog Content -->
<main class="blog-post-container">
    <div class="blog-inner">
        <h1 class="blog-title"><?= htmlspecialchars($blog['title']) ?></h1>
        <p class="blog-meta">
            By <strong><?= htmlspecialchars($blog['username']) ?></strong>
            on <?= date('F j, Y', strtotime($blog['created_at'])) ?>
        </p>
        <article class="blog-content">
            <?= $Parsedown->text($blog['content']) ?>
        </article>
    </div>
</main>

</body>
</html>



