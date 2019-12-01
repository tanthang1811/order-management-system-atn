<?php include("inc/header.php") ?>
<?php include("inc/navbar.php") ?>
<?php require "config/database.php"?>
<?php require 'config/auth.php' ?>

<?php
    // Get all customers
    $sql = "SELECT * FROM customer";
    $result = $conn->query($sql);

    $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_customers = $customers;

    mysqli_free_result($result);

?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <ul class="list-group">
        <?php foreach($customers as $customer): ?>
            <li class="list-group-item wow fadeIn" data-wow-duration="1.5s">
                   <p><b>Customer ID:</b> <?php echo $customer['customerID']?></p> 
                   <p><b>Customer Name:</b> <?php echo $customer['customerName']?></p> 
                   <p><b>Customer Address:</b> <?php echo $customer['customerAddress']?></p> 
                   <p><b>Customer Phone Number:</b> <?php echo $customer['customerPhoneNumber']?></p> 
            </li>
        <?php endforeach; ?>
        </ul>
        </div>
    </div>
</div>

<?php include("inc/footer.php") ?>