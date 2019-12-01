<?php include("inc/header.php")?>
<?php include("inc/navbar.php")?>
<?php require "config/database.php" ?>
<?php require 'config/auth.php' ?>

<?php
    $agencyID = $_SESSION['login_agency_id'];

    // Result per page
    $result_per_page = 5;

    // Get all orders
    $sql = "SELECT * FROM orders WHERE agencyID=$agencyID";
    $result = $conn->query($sql);
    $number_of_results = mysqli_num_rows($result);

    // Number of pages
    $number_of_pages = ceil($number_of_results/$result_per_page);

    // Get current page
    if(!isset($_REQUEST['page'])){
        $current_page = 1;
    } else {
        $current_page = $_REQUEST['page'];
    }

    // Handle pagination
    $this_page_first_result = ($current_page - 1) * $result_per_page;

    // Get all orders with limit
    $sql = "SELECT * FROM orders WHERE agencyID=$agencyID ORDER BY orderDate DESC LIMIT $this_page_first_result, $result_per_page";
    $result = $conn->query($sql);

    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_orders = $orders;

    mysqli_free_result($result);
?>

<div class="container">
    <!-- Order Row -->
    <div class="row">

        <?php foreach($orders as $order): ?>
        <!-- Order Item -->
        <div class="col-12 order-item card wow fadeIn" data-wow-duration="1.5s">
            <div class="card-body">
                <div class="card-title">
                    <p><b>Order ID:</b> <?php echo $order['orderID'] ?></p>
                    <p><b>Customer ID:</b> <?php echo $order['customerID'] ?></p>
                </div>
                <div class="card-text">
                    <ul>
                        <li>
                            <p><b>Issued date:</b> <?php echo $order['orderDate'] ?></p>
                        </li>
                        <li>
                            <p><b>Shipping date:</b> <?php echo $order['shippingDate'] ?></p>
                        </li>
                        <li>
                            <p><b>Total:</b> <?php echo $order['total'] ?>$</p>
                        </li>
                    </ul>
                </div>
                <a href="order-detail.php?orderID=<?php echo $order['orderID'] ?>" class="btn btn-primary">View Order
                    Detail</a>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <?php if(!empty($orders)): ?>
    <!-- Pagination -->
    <div class="pagination-container wow fadeIn" data-wow-duration="1.5s">
        <ul class="pagination justify-content-center">
            <?php if($current_page==1): ?>
            <li class="page-item disabled">
                <a class="page-link" href="order-list.php?page=<?php echo $current_page-1 ?>">
                    Previous
                </a>
            </li>
            <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="order-list.php?page=<?php echo $current_page-1 ?>">
                    Previous
                </a>
            </li>
            <?php endif; ?>

            <?php for ($i=1; $i <= $number_of_pages; $i++):?>
            <li class="page-item">
                <a class="page-link" href="order-list.php?page=<?php echo $i ?>">
                    <?php echo $i ?>
                </a>
            </li>
            <?php endfor; ?>

            <?php if($current_page==$number_of_pages): ?>

            <li class="page-item disabled">
                <a class="page-link" href="order-list.php?page=<?php echo $current_page+1 ?>">
                    Next
                </a>
            </li>
            <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="order-list.php?page=<?php echo $current_page+1 ?>">
                    Next
                </a>
            </li>
            <?php endif; ?>

        </ul>
    </div>
    <?php else: ?>
    <!-- Notification -->
    <div class="text-center">
        <h1 class='text-center'>This account has no order<h1>
        <a class='text-center btn btn-primary' href="add-order.php">Create a new one now</a>
    </div>
    <?php endif; ?>

</div>

<?php include("inc/footer.php")?>