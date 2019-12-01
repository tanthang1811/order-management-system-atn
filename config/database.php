<?php require "config.php" ?>
<?php
    $conn = mysqli_connect(SERVER_NAME, USERNAME, PASSWORD, DATABASE_NAME);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>