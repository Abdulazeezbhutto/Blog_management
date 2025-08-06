<?php
session_start();
require_once("../require/database_connection.php");

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}
?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
      <div class="container mt-4">
        <h2 class="mb-4">📝 Add New Post</h2>
        <h6 class="mb-4"><?php echo $_GET['msg']??""?></h6>


          <form class="card p-4 shadow-sm" method="POST" enctype="multipart/form-data" action="../process.php">

            <!-- Hidden User ID -->
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['user_id']; ?>">

            <!-- Post Title -->
            <div class="mb-3">
              <label for="title" class="form-label">Post Title</label>
              <input type="text" id="title" name="title" class="form-control" placeholder="Enter post title">
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="description" class="form-label">Post summary</label>
              <textarea id="description" name="summary" rows="3" class="form-control" placeholder="Write your post content"></textarea>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label for="description" class="form-label">Post Description</label>
              <textarea id="description" name="description" rows="5" class="form-control" placeholder="Write your post content"></textarea>
            </div>

            <!-- Category -->
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <select id="category" name="category" class="form-select">
                <option selected>-- Select Category --</option>
                <?php
                $query = "SELECT * FROM post_category";
                $result = mysqli_query($connection, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['cat_id'] . '">' . htmlspecialchars($row['cate_name']) . '</option>';
                    }
                } else {
                    echo '<option value="">Not Found...!</option>';
                }
                ?>
              </select>
            </div>

            <!-- Tags (Multiple Selection) -->
            <div class="mb-3">
              <label for="tags" class="form-label">Tags</label>
              <select id="tags" name="tags[]" class="form-select" multiple>
                <?php
                $query = 'SELECT * FROM tags';
                $result = mysqli_query($connection, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['tag_id'] . '">' . htmlspecialchars($row['tag_name']) . '</option>';
                    }
                } else {
                    echo '<option value="">Not Found...!</option>';
                }
                ?>
              </select>
              <small class="text-muted">Hold <b>Ctrl</b> (Windows) or <b>Cmd</b> (Mac) to select multiple tags</small>
            </div>

            <!-- Feature Image -->
            <div class="mb-3">
              <label for="post_image" class="form-label">Feature Image</label>
              <input type="file" id="post_image" name="post_image" class="form-control">
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" name="submit" value="add_post">Add Post</button>
              <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>

          </form>
      </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
