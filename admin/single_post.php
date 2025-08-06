<?php
session_start();
require_once("../require/database_connection.php");

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("location: all_posts.php?msg=Invalid Post ID&color=red");
    exit;
}

$post_id = intval($_GET['id']);

// Fetch post details
$query = "
    SELECT 
        p.post_id, 
        p.post_title, 
        p.post_summary, 
        p.post_description, 
        p.featured_image, 
        p.created_at, 
        c.cate_name AS category, 
        CONCAT(u.first_name, ' ', u.last_name) AS author
    FROM post p
    LEFT JOIN post_category c ON p.cat_id = c.cat_id
    LEFT JOIN users u ON p.user_id = u.user_id
    WHERE p.post_id = '$post_id'
";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("location: all_posts.php?msg=Post not found&color=red");
    exit;
}

$post = mysqli_fetch_assoc($result);

// Fetch tags
$tags_query = "
    SELECT t.tag_name 
    FROM post_tags pt
    INNER JOIN tags t ON pt.tag_id = t.tag_id
    WHERE pt.post_id = '$post_id'
";
$tags_result = mysqli_query($connection, $tags_query);

$tags_list = [];
if ($tags_result && mysqli_num_rows($tags_result) > 0) {
    while ($tag_row = mysqli_fetch_assoc($tags_result)) {
        $tags_list[] = $tag_row['tag_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['post_title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      .post-image {
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
        width: 100%;
      }
      .tag-badge {
        margin: 2px;
        font-size: 13px;
      }
    </style>
  </head>
    <body>
        <div class="container mt-4">

          <!-- Back Button -->
          <div class="mb-3">
            <a href="all_posts.php" class="btn btn-secondary">
              <i class="bi bi-arrow-left"></i> Back to Posts
            </a>
          </div>

          <!-- Post Title -->
          <h1 class="mb-2"><?php echo htmlspecialchars($post['post_title']); ?></h1>

          <!-- Post Meta -->
          <p class="text-muted">
            <span class="badge bg-primary"><?php echo $post['category'] ?? 'Uncategorized'; ?></span> |
            By <strong><?php echo $post['author'] ?? 'Unknown'; ?></strong> |
            <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
          </p>

          <!-- Featured Image -->
          <img src="../<?php echo !empty($post['featured_image']) ? $post['featured_image'] : 'uploads/default.jpg'; ?>" 
              alt="Post Image" class="post-image mb-4">

          <!-- Post Summary -->
          <?php if (!empty($post['post_summary'])): ?>
            <h5>Summary</h5>
            <p><?php echo nl2br(htmlspecialchars($post['post_summary'])); ?></p>
          <?php endif; ?>

          <!-- Post Description -->
          <h5>Description</h5>
          <p><?php echo nl2br(htmlspecialchars($post['post_description'])); ?></p>

          <!-- Tags -->
          <div class="mt-4">
            <h6>Tags:</h6>
            <?php 
            if (!empty($tags_list)) {
                foreach ($tags_list as $tag) {
                    echo "<span class='badge bg-info text-dark tag-badge'>$tag</span>";
                }
            } else {
                echo "<span class='badge bg-secondary'>No Tags</span>";
            }
            ?>
          </div>

        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
