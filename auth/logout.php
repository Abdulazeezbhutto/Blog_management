<?php
    // Start the session
    session_start();

    // Destroy the session
    session_destroy();

    // Redirect to login page with a logout message
    header("Location: login.php?msg=logout+successfully");
    exit();
?>