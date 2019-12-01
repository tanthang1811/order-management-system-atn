<?php
    session_start();

    if (!isset($_SESSION["login_agency_id"])){
        header("location: index.php");
    } 
?>