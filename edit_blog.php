<?php
// Include configuration and helper functions (DB connection, session handling)
require 'config.php';

// Ensure the user is logged in before accessing this page
require_login();

// Get current user's ID and username from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Get blog ID from URL query parameter
$blog_id = $_GET['id'] ?? null;

if (!$blog_id) {
    header('Location: profile.php');  // Redirect to profile if no blog ID provided
    exit;
}

// Fetch the blog post from database, ensuring it belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM blogpost WHERE id = ? AND user_id = ?");
$stmt->execute([$blog_id, $user_id]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header('Location: profile.php');  // Redirect to profile if blog not found or does not belong to user
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if ($title && $content) {
        // Update blog title, content, and timestamp in the database
        $update_stmt = $pdo->prepare("UPDATE blogpost SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
        $update_stmt->execute([$title, $content, $blog_id, $user_id]);
        header('Location: profile.php');  // Redirect back to profile after successful edit
        exit;
    } else {
        $error = "Title and content cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Blog - Lilium</title>

<link rel="stylesheet" href="profile.css">
<link rel="stylesheet" href="edit_blog.css">

<!-- EasyMDE CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">

<!-- Fonts -->
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
    <a href="create_blog.php" class="write-link">✍🏻 Write Something...</a>
    <a href="profile.php" class="profile-circle" title="<?= htmlspecialchars($username) ?>">
      <?= strtoupper(substr($username, 0, 1)) ?>
    </a>
  </nav>
</header>

<!-- Main Content -->
<main class="edit-form">
    <h2>Edit Blog Post</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" placeholder="Title" required>
        <textarea name="content" id="content"><?= htmlspecialchars($blog['content']) ?></textarea>
        <button type="submit">Save Changes</button>
    </form>
</main>

<!-- EasyMDE JS -->
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Initialize EasyMDE editor with autosave and custom settings
  const easyMDE = new EasyMDE({
    element: document.getElementById("content"),
    spellChecker: false,
    autosave: {
      enabled: true,
      uniqueId: "edit_blog_<?= $blog_id ?>",
      delay: 1000
    },
    minHeight: "400px",
    renderingConfig: {
      singleLineBreaks: false,
      codeSyntaxHighlighting: true,
    },
  });

  // Custom image upload (same as in create_blog.php)
  function uploadFile(file) {
    const formData = new FormData();
    formData.append("file", file);

    fetch("upload_image.php", {
      method: "POST",
      body: formData,
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const cm = easyMDE.codemirror;
        const pos = cm.getCursor();
        cm.replaceRange(`![](${data.url})`, pos);
      } else {
        alert(data.error || "Upload failed");
      }
    })
    .catch(() => alert("Upload error"));
  }

  //  Replace EasyMDE's default image button with upload
  easyMDE.toolbar.forEach(button => {
    if (button.name === "image") {
      button.action = function customImageUpload(editor) {
        const input = document.createElement("input");
        input.type = "file";
        input.accept = "image/*";
        input.onchange = (event) => {
          const file = event.target.files[0];
          if (file) uploadFile(file);
        };
        input.click();
      };
    }
  });
});
</script>

</body>
</html>



