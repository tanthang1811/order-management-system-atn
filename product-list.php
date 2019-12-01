<?php include("inc/header.php")?>
<?php include("inc/navbar.php")?>
<?php require "config/database.php" ?>
<?php require 'config/auth.php' ?>

<?php 
    // Get all products
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_products = $products;

    mysqli_free_result($result);

    // Result per page
    $result_per_page = 8;

    // Get all products
    $number_of_results = count($products);

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

    // Get all products with limit
    $sql = "SELECT * FROM product LIMIT $this_page_first_result, $result_per_page";

    $result = mysqli_query($conn, $sql);

    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $og_products = $products;

    mysqli_free_result($result);

    // Get all category
    $sql = "SELECT * FROM category";
    $result = $conn->query($sql);

    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_free_result($result);

    if (isset($_POST['submit'])){
        // Set the sorted list as empty
        $mySortedList = [];

        // Get both of the field in form
        $search = $_POST["search"];
        $category = $_POST['category'];

        // If both of them are default values
        // Return the proucts as normal
        if (empty($search) && $category=="all"){
            $products = $og_products;
        }
        
        // Else
        else {
            // If the $search value is not empty
            // And the $category has a default value
            if (!empty($search) && $category=="all"){
                // Then only check for the name
                foreach ($products as $item) {
                    if (strpos(strtolower($item['productName']), strtolower($search)) !== false){
                        $mySortedList[] = $item;
                    }
                }
            }

            // If the $search value is not empty
            // And the $category has a not default value
            elseif (!empty($search) && $category!="all"){
                // Then only check for the name
                // and the category
                foreach ($products as $item) {
                    if (strpos(strtolower($item['productName']), strtolower($search)) !== false
                    && $item['categoryID'] == $category){
                        $mySortedList[] = $item;
                    }
                }
            } 

            // If the $search value is empty
            // And the $category has a not default value
            else {
                // Then only check for the category
                foreach ($products as $item) {
                    if ($item['categoryID'] == $category){
                        $mySortedList[] = $item;
                    }
                }
            }

            // Set the products value equals to the list 
            // that has been sorted
            $products = $mySortedList;
        }
    }

    

?>

<div class="container">
    <!-- Search Bar -->
    <div class="row mb-4 wow fadeIn" data-wow-duration="1.5s">
        <div class="col-12">
            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST">
                <!-- Search Form Group -->
                <div class="form-group">
                    <label for="search">
                        <h5>Search Product By Name:</h5>
                    </label>
                    <input type="text" id="search" name="search" class="form-control"
                        placeholder="Enter Product Name..." />
                </div>

                <!-- Category Form Group -->
                <div class="form-group">
                    <label for="search">
                        <h5>Search Product By Category:</h5>
                    </label>
                    <select id="category" name="category" class="custom-select">
                        <option selected value="all">All Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['categoryID'] ?>"><?php echo $category['categoryName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Form Group -->
                <div class="form-group">
                    <input type="submit" value="Search" id="submit" name="submit" class="btn btn-primary" />
                    <input type="reset" value="Reset" id="reset" name="reset" class="btn btn-dark" />
                </div>

            </form>
        </div>
    </div>

    <!-- Product Row -->
    <div class="row justify-content-around">
        <?php foreach ($products as $product): ?>
            <!-- Product Item -->
        <figure class="col-lg-3 col-md-6 col-sm-12 product-item wow fadeInUp" data-wow-duration="1.5s">
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
                <a class="btn btn-primary" href="product-detail.php?productID=<?php echo $product['productID'] ?>">View Detail</a>
            </figcaption>
        </figure>
        <?php endforeach; ?>
        
        <div class="col-12">
            <!-- Pagination -->
            <div class="pagination-container wow fadeIn" data-wow-duration="1.5s">
                <ul class="pagination justify-content-center">
                    <?php if($current_page==1): ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $current_page-1 ?>">
                            Previous
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $current_page-1 ?>">
                            Previous
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i=1; $i <= $number_of_pages; $i++):?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $i ?>">
                            <?php echo $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if($current_page==$number_of_pages): ?>

                    <li class="page-item disabled">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $current_page+1 ?>">
                            Next
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $current_page+1 ?>">
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