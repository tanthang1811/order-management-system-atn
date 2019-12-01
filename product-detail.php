<?php include("inc/header.php")?>
<?php include("inc/navbar.php")?>
<?php require "config/database.php" ?>
<?php require 'config/auth.php' ?>

<?php
    if (isset($_REQUEST['productID'])){
        $productID = $_REQUEST['productID'];

        // Get a product
        $sql = "SELECT * FROM product WHERE productID=$productID";
        $result = $conn->query($sql);

        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $product = $products[0];

        mysqli_free_result($result);

        // Get a supplier
        $supplierID = $product['supplierID'];
        $sql = "SELECT * FROM supplier WHERE supplierID=$supplierID";
        $result = $conn->query($sql);

        $suppliers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $supplier = $suppliers[0];

        mysqli_free_result($result);

        // Get a category
        $categoryID = $product['categoryID'];
        $sql = "SELECT * FROM category WHERE categoryID=$categoryID";
        $result = $conn->query($sql);

        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $category = $categories[0];

        mysqli_free_result($result);
    }

?>

<div class="container">
    <div class="row justify-content-around">
        <!-- Product Detail IMG --> 
        <div class="product-detail-img col-lg-3 col-md-4 col-sm-12 wow fadeInLeft" data-wow-duration="1.5s">
            <img src="<?php echo $product['productImageURL']?>" alt="" class="img-fluid">
        </div>

        <!-- Product Detail Content --> 
        <div class="product-detail-content col-lg-6 col-md-4 col-sm-12  wow fadeInRight" data-wow-duration="1.5s">
            <h1><?php echo $product['productName']?></h1>
            <ul>
                <li>
                    <h4>Price: <?php echo $product['productPrice']?>$/item</h4>
                </li>
                <li>
                    <h4>Available Quantity: <?php echo $product['availableQuantity']?></h4>
                </li>
                <li>
                    <h4>Supplier: <?php echo $supplier['supplierName']?></h4>
                </li>
                <li>
                    <h4>Category: <?php echo $category['categoryName']?></h4>
                </li>
            </ul>
            <p><?php echo $product['productDescription']?></p>
            <a href="product-list.php" class="btn btn-primary">
                Back
            </a>
        </div>
    </div>
</div>

<?php include("inc/footer.php")?>
