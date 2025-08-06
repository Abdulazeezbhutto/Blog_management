<?php
// Header
include("includes/header.php");

// Navbar
include("includes/nav_bar.php");

// Database connection
require_once("require/database_connection.php");

// Sanitize post_id
$post_id = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;

// Fetch comments with user data
$query = "SELECT 
            pc.comment_id,
            pc.comment_message,
            pc.created_at,
            u.first_name AS commenter_name,
            u.image_path
          FROM post_comments pc
          INNER JOIN users u ON pc.user_id = u.user_id
          WHERE pc.post_id = $post_id
          ORDER BY pc.created_at DESC";

$result = mysqli_query($connection, $query);
?>

<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Comments</h5>
      <a href="single_blog.php?post_id=<?php echo $post_id; ?>" class="btn btn-sm btn-outline-primary">See Post</a>
    </div>

    <div class="card-body">
      <!-- Comment list -->
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="d-flex mb-4 border-bottom pb-3">
            <img src="<?php echo htmlspecialchars($row['image_path'] ?? 'images/default_user.jpg'); ?>" alt="User" class="rounded-circle me-3" width="50" height="50">
            <div>
              <h6 class="mb-1"><?php echo htmlspecialchars($row['commenter_name']); ?></h6>
              <small class="text-muted"><?php echo htmlspecialchars($row['created_at']); ?></small>
              <p class="mb-0 mt-2"><?php echo nl2br(htmlspecialchars($row['comment_message'])); ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="mb-0 mt-2">No comments yet.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Add comment -->
  <?php if (isset($_SESSION['user'])): ?>
    <div class="card mt-4 shadow-sm">
      <div class="card-header bg-light">
        <h6 class="mb-0">Add a Comment</h6>
      </div>
      <div class="card-body">
        <form action="process.php" method="POST">
          <div class="mb-3">
            <label for="comment" class="form-label">Comment</label>
            <textarea class="form-control" id="comment" rows="4" placeholder="Write your comment..." name="comment" required></textarea>
          </div>
          <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
          <button type="submit" class="btn btn-primary" name="submit" value="add_comment">Post Comment</button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <div class="mt-4 alert alert-info">
      <strong>Login required:</strong> Please log in to post a comment.
    </div>
  <?php endif; ?>

  <!-- Message -->
  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert alert-success mt-3">
      <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
  <?php endif; ?>
</div>

<?php
// Footer
include("includes/footer.php");
?>
