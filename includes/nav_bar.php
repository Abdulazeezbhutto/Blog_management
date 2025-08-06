<!-- Navbar Start -->

<?php
session_start();


?>
<nav class="main-nav navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img class="logo-main" src="images/logo.svg" alt="logo" />
    </a>

    <!-- Toggle Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <!-- Navigation Links -->
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>

        <?php if (isset($_SESSION['user'])) { ?>
          <!-- User Logged In Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user-circle"></i> <?php echo $_SESSION['user']['first_name'] ?? 'User'; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
              <a class="dropdown-item" href="<?php if($_SESSION['user']["role_id"]==1){
                echo "admin/admin_dashboard.php";
              }elseif($_SESSION['user']['role_id']==2){
                echo "user/user_dashboard.php";
              }?>">Profile</a>
              <a class="dropdown-item" href="#">Settings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="auth/logout.php">Logout</a>
            </div>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="auth/login.php">Login</a>
          </li>
        <?php } ?>
      </ul>

      <!-- Social Icons -->
      <ul class="navbar-nav ml-3 d-flex flex-row">
        <li class="nav-item px-2">
          <a class="nav-link" href="#"><i class="fa fa-facebook"></i></a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link" href="#"><i class="fa fa-twitter"></i></a>
        </li>
        <li class="nav-item px-2">
          <a class="nav-link" href="#"><i class="fa fa-instagram"></i></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar End -->
