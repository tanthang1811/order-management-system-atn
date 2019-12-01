<?php include("inc/header.php")?>
<?php include("inc/navbar.php")?>
<?php require "config/database.php" ?>
<?php require 'config/auth.php' ?>

<?php
    $orderID = $_REQUEST["orderID"];

    // Get all orders detail
    $sql = "SELECT * FROM orderdetail WHERE orderID=$orderID";
    $result = $conn->query($sql);

    $orderdetails = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_orderdetails = $orderdetails;

    mysqli_free_result($result);

    // Get all orders
    $sql = "SELECT * FROM orders WHERE orderID=$orderID";
    $result = $conn->query($sql);

    $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_orders = $orders;

    mysqli_free_result($result);

    // Get customer
    $customerID = $orders[0]['customerID'];
    $sql = "SELECT * FROM customer WHERE customerID=$customerID";
    $result = $conn->query($sql);

    $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $customer = $customers[0];

    mysqli_free_result($result);

    // Get all products
    $sql = "SELECT * FROM product WHERE ";
    
    for ($i=0; $i < count($orderdetails); $i++) { 
        $orderdetail = $orderdetails[$i];

        if (count($orderdetails) == 1 || $i == count($orderdetails)-1){
            $sql .= "productID=".$orderdetail['productID'];
        } 
        elseif (count($orderdetails) > 1){
            $sql .= "productID=".$orderdetail['productID']." OR ";
        }
    }

    $result = $conn->query($sql);

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_products = $products;

    mysqli_free_result($result);
?>

<div class="container wow fadeIn" data-wow-duration="1.5s">
    <div class="row">

        <div class="col-6">
            <p><b>Order ID:</b> <?php echo $orderID ?></p>
        </div>

        <div class="col-6">
            <p><b>Customer Name:</b> <?php echo $customer['customerName'] ?></p>
        </div>

        <div class="col-6">
            <p><b>Order Address:</b> <?php echo $orders[0]['orderAddress'] ?></p>
        </div>

        <div class="col-6">
            <p><b>Customer Phone Number:</b> <?php echo $customer['customerPhoneNumber'] ?></p>
        </div>

        <div class="col-6">
            <p><b>Order Date:</b> <?php echo $orders[0]['orderDate'] ?></p>
        </div>

        <div class="col-6">
            <p><b>Shipping Date:</b> <?php echo $orders[0]['shippingDate'] ?></p>
        </div>

        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Sub-Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i=0; $i < count($products); $i++): ?>
                        <?php 
                            $product = $products[$i]; 
                        ?>
                        <?php 
                            $orderdetail = $orderdetails[$i];
                        ?>
                        <tr>
                            <td><?php echo $product['productID']; ?></td>
                            <td><?php echo $product['productName']; ?></td>
                            <td><?php echo $product['productPrice'];?>$</td>
                            <td><?php echo $orderdetail['quantity'];?></td>
                            <td><?php echo $orderdetail['subTotal'];?>$</td>
                        </tr>
                        <?php if($i == count($products)-1): ?>
                            <tr>
                                <td class="text-right" colspan="4"><b>Total</b></td>
                                <td><?php echo $orders[0]['total'] ?>$</td>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <a href="order-list.php" class="btn btn-light">Back</a>
        </div>

    </div>
</div>

<?php include("inc/footer.php")?>
