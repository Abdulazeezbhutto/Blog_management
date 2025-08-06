<?php
session_start();
require_once("../require/database_connection.php");

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}

// Fetch only admin Users
$query = "
    SELECT user_id, first_name, last_name, email, role_id, created_at, image_path
    FROM users
    WHERE role_id = 1
    ORDER BY created_at DESC
";
$result = mysqli_query($connection, $query);

// Handle query error
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Admin Users - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
      .admin-card {
        transition: all 0.3s ease-in-out;
      }
      .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      }
      .admin-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0px 3px 6px rgba(0,0,0,0.2);
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

        <h2 class="mb-4">🛡️ All Admin Users</h2>

        <div class="row g-4">
          <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <?php
                    // Correct image path with ../
                    $image_path = !empty($row['image_path']) ? '../' . $row['image_path'] : '../uploads/default_user.png';
                  ?>
                  <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card text-center p-3 admin-card h-100">
                      <img src="<?php echo $image_path; ?>" alt="Admin Image" class="admin-img mx-auto">
                      <div class="card-body">
                        <h5 class="card-title mb-1">
                          <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                        </h5>
                        <p class="text-muted mb-1"><?php echo htmlspecialchars($row['email']); ?></p>
                        <span class="badge bg-success mb-2">Admin</span>
                        <p class="small text-muted">Joined: <?php echo date("M d, Y", strtotime($row['created_at'])); ?></p>
                      </div>
                    </div>
                  </div>
              <?php endwhile; ?>
          <?php else: ?>
              <div class='col-12 text-center'><p>No admins found</p></div>
          <?php endif; ?>
        </div>

      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
