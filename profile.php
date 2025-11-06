<?php
// Include configuration and helper functions
require 'config.php';

// Ensure only logged-in users can access this page
require_login();

require 'Parsedown.php';  // Include Parsedown for rendering Markdown content as HTML

$Parsedown = new Parsedown();  // Initialize Parsedown (Markdown parser)

// Get current user's ID and username from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Fetch user's email + bio
$stmt = $pdo->prepare("SELECT email, bio FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$email = $user['email'] ?? 'noemail@example.com';
$bio = $user['bio'] ?? '';

// Fetch user's blog posts
$stmt = $pdo->prepare("SELECT * FROM blogpost WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();

// Function to extract first image from Markdown
function get_first_image($content) {
    if (preg_match('/!\[.*?\]\((.*?)\)/', $content, $matches)) return $matches[1]; // Check Markdown image syntax ![alt](url)
    if (preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches)) return $matches[1]; // Check HTML <img> tag
    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($username) ?>’s Profile - Lilium</title>
<link rel="stylesheet" href="profile.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://api.fontshare.com/v2/css?f[]=noe-display@700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
  <nav class="sidebar-nav">
    <a href="dashboard.php">🏠 Home</a>
    <a href="profile.php" class="active">👤 Profile</a>
  </nav>
  <a href="logout.php" class="sidebar-logout">Logout</a>
</aside>

<!-- Topbar -->
<header class="topbar">
  <div class="logo">Lilium</div>
  <nav class="top-nav">
    <a href="dashboard.php" class="write-link">Home</a>
    <a href="create_blog.php" class="write-link">✍🏻 Write Something...</a>
    <a href="profile.php" class="profile-circle" title="<?= htmlspecialchars($username) ?>">
      <?= strtoupper(substr($username, 0, 1)) ?>
    </a>
  </nav>
</header>

<!-- Main Content -->
<main class="profile-main">
  <div class="profile-container">
    <h2><?= htmlspecialchars($username) ?>’s Blogs</h2>

    <?php if (empty($posts)): ?>
      <p class="no-posts">You haven’t written any blogs yet.</p>
    <?php else: ?>
      <div class="blog-list">
        <?php foreach ($posts as $post): 
          $image_url = get_first_image($post['content']);
          $excerpt = strip_tags($Parsedown->text(substr($post['content'], 0, 250)));
        ?>
        <div class="blog-card" onclick="window.location='view_blog.php?id=<?= $post['id'] ?>'">
          <div class="blog-content-left">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p class="meta">Published: <?= date("F j, Y", strtotime($post['created_at'])) ?></p>
            <div class="preview blog-content"><?= $excerpt ?>...</div>
            <a href="view_blog.php?id=<?= $post['id']; ?>" class="read-more" onclick="event.stopPropagation();">Read more</a>
          </div>

          <!-- Optional thumbnail if image exists -->
          <?php if ($image_url): ?>
          <div class="blog-thumbnail">
            <img src="<?= htmlspecialchars($image_url) ?>" alt="Blog Image">
          </div>
          <?php endif; ?>

          <!-- Dropdown on top-right of blog card -->
          <div class="dropdown blog-card-dropdown" onclick="event.stopPropagation();">
            <button class="dropbtn">⋮</button>
            <div class="dropdown-content">
              <a href="view_blog.php?id=<?= $post['id'] ?>">View</a>
              <a href="edit_blog.php?id=<?= $post['id'] ?>">Edit</a>
              <a href="javascript:void(0)" onclick="confirmDelete('<?= htmlspecialchars($post['title']) ?>', 'delete_blog.php?id=<?= $post['id'] ?>')">Delete</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Right Sidebar -->
  <aside class="right-sidebar">
    <div class="profile-circle-large"><?= strtoupper(substr($username, 0, 1)) ?></div>
    
    <div class="user-info">
      <p class="username"><?= htmlspecialchars($username) ?></p>
      <p class="email"><?= htmlspecialchars($email) ?></p>
    </div>

    <!-- Bio Section -->
    <div class="bio-display">
      <p id="bio-text"><?= $bio ? htmlspecialchars($bio) : 'No bio yet.' ?></p>
      <span id="edit-bio-btn" class="edit-bio-link"><?= $bio ? 'Edit Bio' : 'Add Bio' ?></span>
    </div>

    <!-- Bio edit form (hidden by default) -->
    <form class="bio-edit hidden" method="post" action="edit_bio.php">
      <textarea name="bio" rows="4"><?= htmlspecialchars($bio) ?></textarea>
      <div class="bio-buttons">
        <button type="submit">Save</button>
        <button type="button" id="cancel-bio">Cancel</button>
      </div>
    </form>
  </aside>
</main>

<script>
// Reload page if loaded from back/forward cache
window.addEventListener("pageshow", function(event){
    if (event.persisted) window.location.reload();
});

// DOMContentLoaded ensures elements exist before attaching event listeners
document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.querySelector('#edit-bio-btn');
    const bioDisplay = document.querySelector('.bio-display');
    const bioEdit = document.querySelector('.bio-edit');
    const cancelBtn = document.querySelector('#cancel-bio');

    // Toggle bio edit form
    editBtn.addEventListener('click', () => {
        bioDisplay.classList.add('hidden');
        bioEdit.classList.remove('hidden');
        bioEdit.querySelector('textarea').focus();
    });

    // Cancel editing bio
    cancelBtn.addEventListener('click', () => {
        bioEdit.classList.add('hidden');
        bioDisplay.classList.remove('hidden');
    });

    // Dropdown toggle for each blog card
    document.querySelectorAll('.dropbtn').forEach(button => {
        button.addEventListener('click', e => {
            e.stopPropagation();
            button.nextElementSibling.classList.toggle('show');
        });
    });
    // Close dropdown if clicked outside
    window.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-content').forEach(dc => dc.classList.remove('show'));
    });
});

// Confirm deletion of a blog post
function confirmDelete(title,url){
    if(confirm(`Are you sure you want to delete "${title}"? This cannot be undone.`)){
        window.location.href=url;
    }
}
</script>
</body>
</html>
