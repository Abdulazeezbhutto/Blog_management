<?php
include_once("includes/header.php");

?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-lg border-0 rounded-4 p-4 " style="max-width: 700px;">
    <h3 class="mb-4 text-center fw-bold">Create Your Account</h3>
    <h5 class="mb-4 text-center fw-bold"><?php echo $_GET['msg']??""?></h5>


    <form method="POST" action="../process.php" enctype="multipart/form-data">

      <div class="row g-3">
        <!-- User First Name -->
        <div class="col-md-4">
          <label for="firstName" class="form-label">First Name</label>
          <input type="text" class="form-control" id="firstName" name="first_name" placeholder="John" required>
        </div>

        <!-- Middle Name -->
        <div class="col-md-4">
          <label for="middleName" class="form-label">Middle Name</label>
          <input type="text" class="form-control" id="middleName" name="middle_name" placeholder="Remy">
        </div>

        <!-- Last Name -->
        <div class="col-md-4">
          <label for="lastName" class="form-label">Last Name</label>
          <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Santos" required>
        </div>

        <!-- Email -->
        <div class="col-md-6">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
        </div>

        <!-- Password -->
        <div class="col-md-6">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
        </div>

        <!-- Gender -->
        <div class="col-md-6">
          <label class="form-label d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
            <label class="form-check-label" for="male">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="female" value="female">
            <label class="form-check-label" for="female">Female</label>
          </div>
        </div>

        <!-- Date of Birth -->
        <div class="col-md-6">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob" required>
        </div>

        <!-- Address -->
        <div class="col-md-12">
          <label for="address" class="form-label">Address</label>
          <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter your address" required></textarea>
        </div>

        <!-- Contact No -->
        <div class="col-md-6">
          <label for="contact" class="form-label">Contact No</label>
          <input type="tel" class="form-control" id="contact" name="contact_no" placeholder="03XXXXXXXXX" pattern="03[0-9]{9}" required>
        </div>

        <!-- Profile Image -->
        <div class="col-md-6">
          <label for="profileImage" class="form-label">Profile Image</label>
          <input type="file" class="form-control" id="profileImage" name="profile" accept="image/*">
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" name="submit" value="register">Sign Up</button>
      </div>
    </form>

    <p class="mt-4 mb-0 text-center">
      <small class="text-muted">
        Already have an account? <a href="login.php" class="text-decoration-none fw-semibold text-primary">Login here</a>
      </small>
    </p>
  </div>
</div>






