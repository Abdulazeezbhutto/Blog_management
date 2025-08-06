<?php
session_start();
require_once("../require/database_connection.php");

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}

// Fetch all posts with category & author name
$query = "
    SELECT 
        p.post_id, 
        p.post_title, 
        p.featured_image, 
        p.created_at, 
        c.cate_name AS category, 
        CONCAT(u.first_name, ' ', u.last_name) AS author
    FROM post p
    LEFT JOIN post_category c ON p.cat_id = c.cat_id
    LEFT JOIN users u ON p.user_id = u.user_id
    ORDER BY p.created_at DESC
";
$result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Posts - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .post-card img {
      height: 180px;
      object-fit: cover;
    }
    .tags span {
      margin: 2px;
      font-size: 12px;
    }
  </style>
</head>
<body>
<div class="container mt-4">

  <!-- Back Button -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="admin_dashboard.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
  </div>

  <h2 class="mb-4">📄 All Posts</h2>

  <div class="row g-4">
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {

        // Fetch tags for each post
        $tags_query = "
          SELECT t.tag_name 
          FROM post_tags pt
          INNER JOIN tags t ON pt.tag_id = t.tag_id
          WHERE pt.post_id = {$row['post_id']}
        ";
        $tags_result = mysqli_query($connection, $tags_query);

        $tags_list = [];
        if ($tags_result && mysqli_num_rows($tags_result) > 0) {
            while ($tag_row = mysqli_fetch_assoc($tags_result)) {
                $tags_list[] = $tag_row['tag_name'];
            }
        }

        // Updated image path with ../ 
        $image_path = $row['featured_image'] ? '../' . $row['featured_image'] : '../uploads/default.jpg';
        ?>
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="card post-card h-100 shadow-sm">
            
            <!-- Clickable Image -->
            <a href="single_post.php?id=<?php echo $row['post_id']; ?>">
              <img src="<?php echo $image_path; ?>" class="card-img-top" alt="Post Image">
            </a>

            <div class="card-body">
              <!-- Clickable Title -->
              <h5 class="card-title text-truncate">
                <a href="single_post.php?id=<?php echo $row['post_id']; ?>" class="text-decoration-none text-dark">
                  <?php echo $row['post_title']; ?>
                </a>
              </h5>

              <p class="card-text mb-1">
                <span class="badge bg-primary"><?php echo $row['category'] ?? 'Uncategorized'; ?></span>
              </p>

              <p class="tags">
                <?php 
                if (!empty($tags_list)) {
                  foreach ($tags_list as $tag) {
                    echo "<span class='badge bg-info text-dark'>$tag</span>";
                  }
                } else {
                  echo "<span class='badge bg-secondary'>No Tags</span>";
                }
                ?>
              </p>

              <p class="card-text">
                <small class="text-muted">By <?php echo $row['author'] ?? 'Unknown'; ?></small>
              </p>
              <p class="card-text">
                <small class="text-muted"><?php echo date("M d, Y", strtotime($row['created_at'])); ?></small>
              </p>
            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<div class='col-12 text-center'><p>No posts found</p></div>";
    }
    ?>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
