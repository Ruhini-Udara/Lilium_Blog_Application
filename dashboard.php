<?php
// Include configuration and helper functions
require 'config.php';

// Ensure only logged-in users can access this page
require_login();

// Include Parsedown for rendering Markdown content as HTML
require 'Parsedown.php';

$Parsedown = new Parsedown();   // Initialize Parsedown (Markdown parser)

$username = $_SESSION['username'] ?? 'User';  // Get the current username from session or default to 'User'

// Fetch all blog posts along with their author's username
$stmt = $pdo->prepare("
    SELECT b.id, b.title, b.content, b.created_at, u.username
    FROM blogpost b
    JOIN user u ON b.user_id = u.id
    ORDER BY b.created_at DESC
");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to extract the first image URL from Markdown content
function extractFirstImage($content) {
    if (preg_match('/!\[.*?\]\((.*?)\)/', $content, $matches)) {
        return $matches[1];
    }
    return null;
}

// Function to remove all image Markdown syntax from content
function removeImagesFromMarkdown($content) {
    return preg_replace('/!\[.*?\]\(.*?\)/', '', $content);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lilium - Dashboard</title>
<link rel="stylesheet" href="dashboard.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://api.fontshare.com/v2/css?f[]=noe-display@700&display=swap" rel="stylesheet">
</head>
<body>

<!--sidebar-->
<aside class="sidebar">
  <nav class="sidebar-nav">
    <a href="dashboard.php" class="active">🏠 Home</a>
    <a href="profile.php">👤 Profile</a>
  </nav>
  <a href="logout.php" class="sidebar-logout">Logout</a>
</aside>

<!--topbar-->
<header class="topbar">
  <div class="logo">Lilium</div>
  <nav class="top-nav">
    <a href="create_blog.php" class="write-link">✍🏻 Write Something...</a>
    <a href="profile.php" class="profile-circle" title="<?= htmlspecialchars($username) ?>">
      <?= strtoupper(substr($username, 0, 1)) ?>
    </a>
  </nav>
</header>

<!-- Main dashboard content -->
<main class="dashboard-container">
  <h2>Recent Blog Posts</h2>

  <?php if (empty($blogs)): ?>
      <p class="no-posts">No blog posts yet. Be the first to write one!</p>
  <?php else: ?>
      <div class="blog-list">
        <?php foreach ($blogs as $blog): 
            $imageUrl = extractFirstImage($blog['content']);
            $cleanContent = removeImagesFromMarkdown($blog['content']);
        ?>
          <article class="blog-card" onclick="window.location='view_blog.php?id=<?= $blog['id'] ?>'">
            <div class="blog-content-left">
              <h3><?= htmlspecialchars($blog['title']) ?></h3>
              <p class="meta">
                By <strong><?= htmlspecialchars($blog['username']); ?></strong> 
                on <?= date('F j, Y', strtotime($blog['created_at'])); ?>
              </p>
              <div class="preview blog-content">
                <?= $Parsedown->text(substr($cleanContent, 0, 250)) ?>...
              </div>
              <a href="view_blog.php?id=<?= $blog['id']; ?>" class="read-more">Read more</a>
            </div>

            <?php if ($imageUrl): ?>
              <div class="blog-thumbnail">
                <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Thumbnail">
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
  <?php endif; ?>
</main>

</body>
</html>












