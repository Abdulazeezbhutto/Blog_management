<?php

    require_once("database_settings.php");

    // database connection

    mysqli_report(false);

    $connection = mysqli_connect($host_name, $user_name, $password, $database_name);

    //checking if connection is establish or not
   if (mysqli_connect_errno()) {
    echo "Connection Error: " . mysqli_connect_error();
    }


?>