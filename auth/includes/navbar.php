<?php
require_once("require/database_connection.php");
?>
<nav class="main-nav navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="user_dashboard.php">
      <img class="logo-main" src="images/logo.svg" alt="logo" />
    </a>

    <!-- Toggle Button -->
    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse nav-list" id="mainNav">
      <!-- Navigation Links Start -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="user_dashboard.php">Home</a>
        </li>

        <!-- Dropdown Component Start -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php
            $query = "SELECT * FROM post_category"; // Make sure table name is correct
            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) { 
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <li>
                      <a class="dropdown-item" href="#">
                        <?php echo htmlspecialchars($row['cate_name']); ?>
                      </a>
                    </li>
                    <?php
                }  
            } else {
                ?>
                <li><a class="dropdown-item" href="#">Not Exist</a></li>
                <?php
            }
            ?>
          </ul>
        </li>
        <!-- Dropdown Component End -->

        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
      </ul>
      <!-- Navigation Links End -->

      <!-- Social Links + Logout Button Start -->
      <ul class="main-nav-social list-inline mb-0 ms-lg-3 d-flex align-items-center gap-2">
        <li class="list-inline-item">
          <a href="#"><i class="fa fa-facebook"></i></a>
        </li>
        <li class="list-inline-item">
          <a href="#"><i class="fa fa-twitter"></i></a>
        </li>
        <li class="list-inline-item">
          <a href="#"><i class="fa fa-instagram"></i></a>
        </li>
        <li class="list-inline-item ms-2">
          <a href="logout.php" class="btn btn-outline-danger btn-sm px-2 py-1" style="font-size: 0.75rem;">Logout</a>
        </li>
      </ul>
      <!-- Social Links + Logout Button End -->
    </div>
  </div>
</nav>
