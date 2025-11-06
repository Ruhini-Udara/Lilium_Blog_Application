<?php
// Include configuration and helper functions
require 'config.php';

// Ensure only logged-in users can access this page
require_login();

// Get current user's ID and username from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    // Check that both title and content are provided
    if ($title && $content) {
        // Insert new blog post into database with timestamps
        $stmt = $pdo->prepare("INSERT INTO blogpost (user_id, title, content, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$user_id, $title, $content]);
        header('Location: profile.php');  // Redirect to profile page after successful creation
        exit;
    } else {
        $error = "Title and content cannot be empty.";   // Display error if title or content is missing
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Blog - Lilium</title>

<link rel="stylesheet" href="create_blog.css">

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
<main class="create-blog-container">
    <h2>Create Blog Post</h2>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Title" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        <textarea name="content" id="content"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
        <button type="submit">Publish</button>
    </form>
</main>

<!-- EasyMDE JS -->
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const easyMDE = new EasyMDE({
    element: document.getElementById("content"),
    spellChecker: false,
    autosave: {
      enabled: true,
      uniqueId: "create_blog_<?= $user_id ?>",
      delay: 1000
    },
    minHeight: "400px",
    renderingConfig: {
      singleLineBreaks: false,
      codeSyntaxHighlighting: true,
    },
  });

  // Override image button manually
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
        // Insert uploaded image Markdown into editor at cursor
        const cm = easyMDE.codemirror;
        const pos = cm.getCursor();
        cm.replaceRange(`![](${data.url})`, pos);
      } else {
        alert(data.error || "Upload failed");
      }
    })
    .catch(() => alert("Upload error"));
  }

  // Replace EasyMDE's default image button
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













