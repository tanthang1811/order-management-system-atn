<?php
    session_start();

    unset($_SESSION["login_agency_id"]);
    $_SESSION = [];

    header("location: index.php");
?>