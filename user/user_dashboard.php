<?php
session_start();
require_once("../require/database_connection.php");

// Check user session
if (isset($_SESSION['user'])) {
    // Redirect admin to admin dashboard
    if ($_SESSION['user']['role_id'] == 1) {
        header("Location: ../admin/admin_dashboard.php");
        exit();
    }

    // Check if user is a normal user (role_id = 2)
    if ($_SESSION['user']['role_id'] == 2) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>

  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #2d323e;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }
    .sidebar a:hover {
      background-color: #3e4451;
    }
    .card-img-top {
      height: 180px;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 sidebar p-3">
        <h4 class="text-center">Blogge</h4>
        <hr>
        <p class="fw-bold text-uppercase">Navigation</p>
        <a href="#"><i class="bi bi-speedometer2"></i> User Dashboard</a>
        <p class="fw-bold text-uppercase mt-4">Logout</p>
        <a href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>    
      </nav>

      <!-- Main Content -->
      <main class="col-md-10 p-4">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
          <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
              aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler">
              <a class="navbar-brand" href="user_dashboard.php">Blogge</a>
              <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
              <a href="../auth/logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
          </div>
        </nav>

        <!-- Welcome Message -->
        <div class="alert alert-primary">
          Hello, Welcome <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? "User"); ?>!
        </div>

        <!-- Blog Posts Section -->
        <div class="container">
          <h2 class="mb-4 text-center fw-bold text-primary">Latest Blog Posts</h2>
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <?php
            $query = "SELECT p.*, c.cate_name 
                      FROM posts p
                      INNER JOIN post_category c ON p.cat_id = c.cat_id 
                      ORDER BY p.created_at DESC";
            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col">
              <div class="card h-100 shadow-sm border-0">
                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                     class="card-img-top" alt="Post Image" />
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                  <p class="text-muted small mb-1">
                    <i class="fa fa-folder me-1 text-secondary"></i>
                    <?php echo htmlspecialchars($row['cat_name']); ?>
                  </p>
                  <p class="card-text">
                    <?php echo substr(htmlspecialchars($row['description']), 0, 100) . '...'; ?>
                  </p>
                </div>
                <div class="card-footer bg-white border-0">
                  <div class="d-flex flex-wrap gap-1">
                    <?php
                    $post_id = $row['post_id'];
                    $tag_query = "SELECT t.tag_name 
                                  FROM post_tags pt 
                                  INNER JOIN tags t ON pt.tag_id = t.tag_id 
                                  WHERE pt.post_id = $post_id";
                    $tag_result = mysqli_query($connection, $tag_query);
                    while ($tag_row = mysqli_fetch_assoc($tag_result)) {
                      echo '<span class="badge bg-light text-dark border">' . htmlspecialchars($tag_row['tag_name']) . '</span>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-info text-center">No posts found.</div></div>';
            }
            ?>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    } // role_id == 2 ends
} else {
    // Not logged in
    header("Location: ../auth/login.php?msg=login first&color=red");
    exit();
}
?>
