<?php
session_start();
require_once("../require/database_connection.php");

// Only allow admins to access this page
if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
    header("location: ../auth/login.php?msg=Login First&color=red");
    exit;
}

// Fetch all users except admins
$query = "
    SELECT user_id, first_name, last_name, email, role_id, created_at
    FROM users
    WHERE role_id != 1
    ORDER BY created_at DESC
";
$result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Users - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
  <body>
    <div class="container mt-4">

      <!-- Back Button -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
      </div>

      <h2 class="mb-4">👥 All Users</h2>

      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined On</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                      <td><?php echo $row['user_id']; ?></td>
                      <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                      <td><?php echo $row['email']; ?></td>
                      <td>
                        <?php 
                          if ($row['role_id'] == 2) echo "<span class='badge bg-primary'>Author</span>";
                          else echo "<span class='badge bg-secondary'>User</span>";
                        ?>
                      </td>
                      <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
                      <td>
                        <a href="update_user.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-warning">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" 
                          class="btn btn-sm btn-danger" 
                          onclick="return confirm('Are you sure you want to delete this user?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
