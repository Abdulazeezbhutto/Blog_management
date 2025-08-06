<?php
session_start();
require_once("../require/database_connection.php");

if (isset($_SESSION["user"]) && $_SESSION["user"]['role_id'] == 1) {
?>

    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
          .card-box {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
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
                <a href="#"><i class="bi bi-speedometer2"></i> Admin Dashboard</a>

                <p class="fw-bold text-uppercase mt-4">Manager</p>
                <a href="all_admins.php"><i class="bi bi-people"></i> Admins</a>
                <a href="all_users.php"><i class="bi bi-person"></i> Manage Users</a>
                <a href="your_posts.php"><i class="bi bi-file-earmark-text"></i> your Posts</a>
                <a href="all_posts.php"><i class="bi bi-file-earmark-text"></i> All posts</a>
                <a href="add_post.php"><i class="bi bi-plus-circle"></i> Add Posts</a>
                <p class="fw-bold text-uppercase mt-4">Logout</p>
                <a href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
              </nav>

              <!-- Main Content -->
              <main class="col-md-10 p-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                  <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                      <a class="navbar-brand" href="#">Blogge</a>
                      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                          <a class="nav-link active" href="all_Admins.php">Admins</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="all_users.php">Manage Users</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="your_posts.php">your Posts</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="all_posts.php">All Posts</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="add_post.php">Add Posts</a>
                        </li>
                      </ul>
                      <a class="btn btn-outline-danger" href="../auth/logout.php">Logout</a>
                    </div>
                  </div>
                </nav>

                <!-- Welcome Message -->
                <div class="h4 pb-2 mt-4 text-primary-emphasis border-top border-success">
                  Hello, Welcome... <?php echo $_SESSION['user']['first_name'] ?? ""; ?>
                </div>

                <!-- Profile Section -->
                <div class="container mt-5">
                  <div class="row">
                    <!-- Profile Image Card -->
                    <div class="col-md-4">
                      <div class="card text-center p-3 shadow-sm">
                        <img src="../<?php echo $_SESSION['user']['image_path']; ?>" 
                            class="rounded-circle mx-auto d-block border border-3 border-primary" 
                            alt="Profile" 
                            width="150" height="150">
                        <div class="card-body">
                          <h5 class="card-title">
                            <?php echo $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']; ?>
                          </h5>
                          <p class="text-muted"><?php echo $_SESSION['user']['email']; ?></p>
                          <a href="profile_edit.php" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                        </div>
                      </div>
                    </div>

                    <!-- Profile Details Card -->
                    <div class="col-md-8">
                      <div class="card p-4 shadow-sm">
                        <h5 class="mb-3">Profile Details</h5>
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item"><strong>Full Name:</strong> <?php echo $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']; ?></li>
                          <li class="list-group-item"><strong>Email:</strong> <?php echo $_SESSION['user']['email']; ?></li>
                          <li class="list-group-item"><strong>Gender:</strong> <?php echo $_SESSION['user']['gender']; ?></li>
                          <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo $_SESSION['user']['date_of_birth']; ?></li>
                          <li class="list-group-item"><strong>Contact No:</strong> <?php echo $_SESSION['user']['contact_no']; ?></li>
                          <li class="list-group-item"><strong>Address:</strong> <?php echo $_SESSION['user']['address']; ?></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </main>
            </div>
          </div>

          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
    </html>

<?php
} elseif (isset($_SESSION["user"]) && $_SESSION["user"]["role_id"] == 2) {
    header("location: ../user/user_dashboard.php");
    exit;
} else {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}
?>
