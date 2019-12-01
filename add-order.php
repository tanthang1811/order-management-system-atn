<?php include("inc/header.php")?>
<?php include("inc/navbar.php")?>
<?php require "config/database.php"?>
<?php require 'config/auth.php' ?>
<?php

    $agencyID = $_SESSION['login_agency_id'];
    $errors = [];

     // Get all customers
     $sql = "SELECT * FROM customer";
     $result = $conn->query($sql);
 
     $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
     $og_customers = $customers;
 
     mysqli_free_result($result);
 

    if (isset($_POST['add-customer'])){
        $customerID = $_POST['customer-radio-group'];

        $_SESSION['customerID'] = $customerID;
    }

    if (isset($_POST['search-customer'])){
        $search_by_name = $_POST['search-by-name'];
        $search_by_name = strtolower($search_by_name);
        $sorted_customers = [];

        $sql = "SELECT * FROM customer";

        $result = $conn->query($sql);

        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $og_customers = $customers;

        if (empty($search_by_name)){
            $customers = $og_customers;
        } else {
            foreach ($customers as $customer) {
                if(strpos(strtolower($customer['customerName']), $search_by_name) !== false){
                    $sorted_customers[] = $customer;
                } else {
                    continue;
                }
            }
            $customers = $sorted_customers;
        }
    }

    if (!isset($_SESSION['cartProductID'])){
        $_SESSION['cartProductID'] = [];
    }
    if (!isset($_SESSION['carts'])){
        $_SESSION['carts'] = [];
    }

    function occurInCartProductID($cartProductIDItem){
        $index = 0;
        foreach ($_SESSION['cartProductID'] as $item) {
            if ($item == $cartProductIDItem)
                $index++;
        }
        if ($index == 0) return false;
        else return true;
    }

    if (isset($_POST["submit"])){
        $carts = $_SESSION['carts'];
        $customerID = $_SESSION['customerID'];
        $orderTotal = 0;
        $orderDate = $_POST["order-date"];
        $shippingDate = $_POST["shipping-date"];
        $orderAddress = $_POST['order-address'];

        for ($i=0; $i < count($carts); $i++) { 
            $cart = $carts[$i];
            $orderTotal += $cart['productPrice'] * $cart['quantity'];
        }  

        if (!$orderTotal == 0){
            $sql = "INSERT INTO orders (customerID, agencyID, total, orderDate, shippingDate, orderAddress) VALUES 
            ('$customerID', '$agencyID', '$orderTotal', '$orderDate', '$shippingDate', '$orderAddress')";

            $result = $conn->query($sql);

            // Get the latest
            $sql = "SELECT * FROM orders";
            $result = $conn->query($sql);

            $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $og_customers = $orders;
            $order = $orders[count($orders)-1];

            mysqli_free_result($result);

            // Create Order Details
            $orderID = $order['orderID'];

            for ($i=0; $i < count($carts); $i++) { 
                $cart = $carts[$i];
                $productID = $cart['productID'];
                $productQuantity = $cart['quantity'];
                $productSubTotal = $cart['quantity'] * $cart['productPrice'];

                $sql = "INSERT INTO orderdetail (productID, orderID, quantity, subTotal) VALUES 
                ('$productID', '$orderID', '$productQuantity', '$productSubTotal')";

                $result = $conn->query($sql);
            }

            $_SESSION['carts'] = [];
            $_SESSION['cartProductID'] = [];
        }
    }

    if (isset($_REQUEST["customerID"])){
        if (isset($_SESSION['customerID'])){
            $_SESSION['customerID'] = $_REQUEST["customerID"];
        } else {
            $_SESSION['customerID'] = "";
            $_SESSION['customerID'] = $_REQUEST["customerID"];
        }
    } 

    if (isset($_POST["remove-form-cart"])){
        foreach ($_SESSION['cartProductID'] as $key => $item) {
            if ($item == $_POST["deletedProductID"]){
                unset($_SESSION['cartProductID'][$key]);
            }
        }

        foreach ($_SESSION['carts'] as $key => $item) {
            if ($item['productID'] == $_POST["deletedProductID"]){
                unset($_SESSION['carts'][$key]);
            }
        }
    }

    if (isset($_POST["add-to-cart"])){
        $quantity = $_POST["quantity"];
        $availableQuantity = $_POST["availableQuantity"];
        $productID = $_POST["productID"];

        if ($availableQuantity < $quantity){
            $errors[] = "Request quantity cannot be greater than the available quantity";
        } else if ($quantity <= 0){
            $errors[] = "Request quantity cannot be lower than or equal to 0";
        } else {
            if (isset($_SESSION['cartProductID'])){
                $_SESSION['cartProductID'][] = $_POST["productID"];
            }
            
            // Get product
            $sql = "SELECT * FROM product WHERE productID=$productID";
            $result = $conn->query($sql);

            $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $og_customers = $products;
            $product = $products[0];

            mysqli_free_result($result);

            if (isset($_SESSION['carts'])){
                $_SESSION['carts'][] = [
                    "productID" => $product['productID'],
                    "productName" => $product['productName'],
                    "quantity" => $quantity,
                    "productPrice" => $product['productPrice'],
                    "subTotal" => $quantity * $product['productPrice']
                ];
            }

        }
    }

    
    // Result per page
    $result_per_page = 8;

    // Get all products
    $sql = "SELECT * FROM product";
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
    $sql = "SELECT * FROM product LIMIT $this_page_first_result, $result_per_page";
    $result = $conn->query($sql);

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_products = $products;

    mysqli_free_result($result);
   
?>

<div class="container new-order-form wow fadeIn" data-wow-duration="1.5s">
    <!-- New Order Form -->
    <h2 class="new-order-form-title"><i class="fas fa-cart-plus"></i> Add New Order</h2>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?php echo $errors[0] ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
        <!-- Customer ID and Agency ID Form Group -->
        <div class="form-group row">
            <!-- Customer ID Input -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="customerID">Customer ID:</label>
                <input type="text" placeholder="Customer ID" name="customerID" id="customerID" class="form-control"
                    readonly required value="<?= (!empty($_SESSION['customerID'])) ? $_SESSION['customerID'] : ""?>" />
            </div>

            <!-- Agency ID Input -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="agencyID">Agency ID:</label>
                <input type="text" placeholder="Agency ID" name="agencyID" id="agencyID" class="form-control" readonly
                    required value="<?php echo $agencyID ?>" />
            </div>
        </div>

        <!-- Address Form Group -->
        <div class="form-group">
            <label for="order-address">Order Address:</label>
            <textarea id="order-address" name="order-address" rows="5" placeholder="Address" class="form-control"></textarea>
        </div>

        <!-- OrderDate and ShippingDate Form Group -->
        <div class="form-group row">
            <!-- Order Date Input -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="order-date">Order Date:</label>
                <input type="date" name="order-date" id="order-date" class="form-control" />
            </div>

            <!-- Shipping Date Input -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="shipping-date">Shipping Date:</label>
                <input type="date" name="shipping-date" id="shipping-date" class="form-control" />
            </div>
        </div>

        <!-- Order Details Table -->
        <div class="form-group row">
            <div class="col-12">
                <label>Order Details:</label>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($_SESSION['carts'] as $cart): ?>
                        <tr>
                            <td><?php echo $cart['productName'] ?></td>
                            <td><?php echo $cart['quantity'] ?></td>
                            <td><?php echo $cart['productPrice'] ?>$</td>
                            <td><?php echo ($cart['productPrice'] * $cart['quantity']); ?>$</td>
                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                                    <input type="hidden" id="deletedProductID" name="deletedProductID"
                                        value="<?php echo $cart['productID'] ?>" />
                                    <input type="submit" name="remove-form-cart" id="remove-form-cart" value="Delete"
                                        class="btn btn-danger" />
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit Form Group -->
        <div class="form-group">
            <?php if(empty($_SESSION['carts']) || empty($_SESSION['customerID'])): ?>
            <input disabled type="submit" value="Create" id="submit" name="submit" class="btn btn-primary" />
            <?php else: ?>
            <input type="submit" value="Create" id="submit" name="submit" class="btn btn-primary" />
            <?php endif; ?>
        </div>
    </form>

    <hr />

    <div class="row">
        <div class="col-12">
            <ul class="d-flex">
                <li>
                    <a href="#" id="show-customers-btn" class="btn btn-info">
                        Show Customers List
                    </a>
                </li>
                <li class="ml-2">
                    <a href="#" id="show-products-btn" class="btn btn-info">
                        Show Products List
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Customer List -->
    <div class="row customer-list">
        <div class="col-12">
            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                <!-- Search By Name -->
                <div class="form-group">
                    <label for="search-by-name">Search By Name:</label>
                    <input type="text" placeholder="Search By Name" class="form-control" id="search-by-name"
                        name="search-by-name">
                </div>

                <!-- Search Customer Form Group -->
                <div class="form-group">
                    <input type="submit" name="search-customer" value="Search Customer" id="search-customer"
                        class="btn btn-primary">
                </div>
            </form>


            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                    <!-- Name Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>True</td>
                                <td>Customer ID</td>
                                <td>Customer Name</td>
                                <td>Customer Address</td>
                                <td>Customer Phone Number</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer):?>
                            <tr>
                                <td>
                                    <input type="radio" name="customer-radio-group"
                                        value="<?php echo $customer['customerID']?>" />
                                </td>
                                <td><?php echo $customer['customerID']?></td>
                                <td><?php echo $customer['customerName']?></td>
                                <td><?php echo $customer['customerAddress']?></td>
                                <td><?php echo $customer['customerPhoneNumber']?></td>
                            <tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>

                <!-- Add Customer Form Group -->
                <div class="form-group">
                    <input type="submit" name="add-customer" value="Add Customer" id="add-customer"
                        class="btn btn-primary">
                </div>
            </form>
        </div>

        <!-- Customer List Prototype 
        <div class="col-12">
            <ul class="list-group">
                <?php foreach($customers as $customer): ?>
                <li class="list-group-item">
                    <p><b>Customer ID:</b> <?php echo $customer['customerID']?></p>
                    <p><b>Customer Name:</b> <?php echo $customer['customerName']?></p>
                    <p><b>Customer Address:</b> <?php echo $customer['customerAddress']?></p>
                    <p><b>Customer Phone Number:</b> <?php echo $customer['customerPhoneNumber']?></p>
                    <a href="add-order.php?customerID=<?php echo $customer['customerID']?>" class="btn btn-primary">
                        Add Customer
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        -->
    </div>

    <!-- Product List -->
    <div class="row d-flex product-list justify-content-around">
        <?php foreach ($products as $product): ?>

        <!-- Product Item -->
        <figure class="col-lg-3 col-md-6 col-sm-12 product-item">
            <img src="<?php echo $product['productImageURL'] ?>" alt="" class="img-fluid">
            <figcaption>
                <!-- Product Name -->
                <h3><?php echo $product['productName'] ?></h3>
                <ul>
                    <li>
                        <!-- Product Price -->
                        <h5>Price: <?php echo $product['productPrice'] ?>$/item</h5>
                    </li>
                    <li>
                        <!-- Available Quantity -->
                        <h5>Available Quantity: <?php echo $product['availableQuantity'] ?></h5>
                    </li>
                </ul>
                <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                    <!-- Quantity Form Group -->
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" required class="form-control" id="quantity" name="quantity">
                    </div>

                    <!-- Submit Form Group -->
                    <div class="form-group">
                        <input type="hidden" id="productID" name="productID"
                            value="<?php echo $product['productID']?>" />
                        <input type="hidden" id="availableQuantity" name="availableQuantity"
                            value="<?php echo $product['availableQuantity']?>" />
                        <?php if(occurInCartProductID($product['productID'])): ?>
                        <input type="submit" class="btn btn-success" value="Add To Cart" name="add-to-cart"
                            id="add-to-cart" disabled />
                        <?php else: ?>
                        <input type="submit" class="btn btn-success" value="Add To Cart" name="add-to-cart"
                            id="add-to-cart" />
                        <?php endif; ?>
                    </div>
                </form>
            </figcaption>
        </figure>
        <?php endforeach; ?>

        <div class="col-12">
            <!-- Pagination -->
            <div class="pagination-container wow fadeIn" data-wow-duration="1.5s">
                <ul class="pagination justify-content-center">
                    <?php if($current_page==1): ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="add-order.php?page=<?php echo $current_page-1 ?>">
                            Previous
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="add-order.php?page=<?php echo $current_page-1 ?>">
                            Previous
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i=1; $i <= $number_of_pages; $i++):?>
                    <li class="page-item">
                        <a class="page-link" href="add-order.php?page=<?php echo $i ?>">
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
        </div>
    </div>



</div>

<?php include("inc/footer.php")?>