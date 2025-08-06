<?php
session_start();
require_once("../require/database_connection.php");

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}

// Check if post ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location: all_posts.php?msg=Post ID missing&color=red");
    exit;
}

$post_id = intval($_GET['id']);

// Fetch post data
$query = "SELECT * FROM post WHERE post_id = '$post_id' LIMIT 1";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("location: all_posts.php?msg=Post not found&color=red");
    exit;
}

$post = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Post - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4">✏️ Update Post</h2>

  <form class="card p-4 shadow-sm" method="POST" enctype="multipart/form-data" action="../process.php">
    
    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['user_id']; ?>">

    <!-- Post Title -->
    <div class="mb-3">
      <label for="title" class="form-label">Post Title</label>
      <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($post['post_title']); ?>" required>
    </div>

    <!-- Summary -->
    <div class="mb-3">
      <label for="summary" class="form-label">Post Summary</label>
      <textarea id="summary" name="summary" rows="3" class="form-control"><?php echo htmlspecialchars($post['post_summary']); ?></textarea>
    </div>

    <!-- Description -->
    <div class="mb-3">
      <label for="description" class="form-label">Post Description</label>
      <textarea id="description" name="description" rows="5" class="form-control" required><?php echo htmlspecialchars($post['post_description']); ?></textarea>
    </div>

    <!-- Category -->
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <select id="category" name="category" class="form-select" required>
        <option value="">-- Select Category --</option>
        <?php
        $cat_query = "SELECT * FROM post_category";
        $cat_result = mysqli_query($connection, $cat_query);
        while ($cat = mysqli_fetch_assoc($cat_result)) {
            $selected = ($cat['cat_id'] == $post['cat_id']) ? "selected" : "";
            echo "<option value='{$cat['cat_id']}' $selected>" . htmlspecialchars($cat['cate_name']) . "</option>";
        }
        ?>
      </select>
    </div>

    <!-- Tags -->
    <div class="mb-3">
      <label for="tags" class="form-label">Tags</label>
      <select id="tags" name="tags[]" class="form-select" multiple>
        <?php
        // Get all tags
        $tags_query = "SELECT * FROM tags";
        $tags_result = mysqli_query($connection, $tags_query);

        // Get current tags for this post
        $post_tags_query = "SELECT tag_id FROM post_tags WHERE post_id = '$post_id'";
        $post_tags_result = mysqli_query($connection, $post_tags_query);
        $current_tags = [];
        while ($pt = mysqli_fetch_assoc($post_tags_result)) {
            $current_tags[] = $pt['tag_id'];
        }

        while ($tag = mysqli_fetch_assoc($tags_result)) {
            $selected = in_array($tag['tag_id'], $current_tags) ? "selected" : "";
            echo "<option value='{$tag['tag_id']}' $selected>" . htmlspecialchars($tag['tag_name']) . "</option>";
        }
        ?>
      </select>
      <small class="text-muted">Hold <b>Ctrl</b> (Windows) or <b>Cmd</b> (Mac) to select multiple</small>
    </div>

    <!-- Feature Image -->
    <div class="mb-3">
      <label for="post_image" class="form-label">Feature Image</label>
      <input type="file" id="post_image" name="post_image" class="form-control">
      <?php if (!empty($post['featured_image'])): ?>
        <div class="mt-2">
          <img src="<?php echo $post['featured_image']; ?>" alt="Post Image" class="img-thumbnail" width="150">
        </div>
      <?php endif; ?>
    </div>

    <!-- Buttons -->
    <div class="d-flex gap-2">
      <button type="submit" name="submit" value="update_post" class="btn btn-success">Update Post</button>
      <a href="all_posts.php" class="btn btn-secondary">Cancel</a>
    </div>
    
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
