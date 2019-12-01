<?php include("inc/header.php") ?>
<?php include("inc/navbar.php") ?>
<?php require "config/database.php" ?>
<?php require 'config/auth.php' ?>

<?php
    $orders = [];
    $total = 0;

    if (isset($_POST['submit'])){
        $beginDate = $_POST['begin-date'];
        $endDate = $_POST['end-date'];

        $sql = "
        SELECT * FROM orders WHERE
        (orderDate BETWEEN '$beginDate' AND '$endDate') OR 
        (shippingDate BETWEEN '$beginDate' AND '$endDate') OR 
        (orderDate <= '$beginDate' AND shippingDate >= '$endDate')
        ";

        $result = $conn->query($sql);

        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $og_orders = $orders;

        mysqli_free_result($result);

        foreach ($orders as $value) {
            $total += $value['total'];
        }
    }
?>

<div class="container report-gen wow fadeIn" data-wow-duration="1.5s">
    <h1><i class="fas fa-file-alt"></i> Report Generator</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="begin-date">From: </label>
                <input type="date" name="begin-date" id="begin-date" class="form-control"/>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="end-date">To: </label>
                <input type="date" name="end-date" id="end-date" class="form-control"/>
            </div>

            <div class="col-12">
                <input type="submit" value="Generate Report" class="btn btn-primary btn-block" id="submit"
                name="submit">
            </div>
        </div>
    </form>

    <hr/>

    <?php if(!empty($orders)): ?>
        <div class="row">
            <div class="col-12">
                <h4 class='text-center mb-4'>Income Report</h4>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><b>From:</b> <?php echo $beginDate ?></p>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><b>To:</b> <?php echo $endDate ?></p>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><b>Total Sales Made:</b> <?php echo count($orders) ?></p>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
                <p><b>Income Made:</b> <?php echo $total ?>$</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("inc/footer.php") ?>