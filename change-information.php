<?php include("inc/header.php") ?>
<?php include("inc/navbar.php") ?>
<?php require "config/database.php"?>
<?php require 'config/auth.php' ?>

<?php 

    if (!isset($_SESSION['msg'])){
        $_SESSION['msg'] = "";
        $_SESSION['msg_class'] = "";
    }

    $agencyID = $_SESSION["login_agency_id"];

    if (isset($_POST["submit"])){
        $agencyName = $_POST['full-name'];
        $agencyUsername = $_POST['username'];
        $agencyAddress = $_POST['address'];
        $agencyPhoneNumber = $_POST['phoneNumber'];

        $sql = "UPDATE agency
        SET agencyName='$agencyName', agencyUsername='$agencyUsername',
        agencyAddress = '$agencyAddress',
        agencyPhoneNumber = '$agencyPhoneNumber'
        WHERE agencyID = $agencyID";
        $result = mysqli_query($conn, $sql);

        $_SESSION['msg'] = "You have successfully update your data";
        $_SESSION['msg_class'] = "alert-success";

        header("location: change-information.php");
    }

    $sql = "SELECT * FROM agency WHERE agencyID=$agencyID";
    $result = mysqli_query($conn, $sql);

    $agencies = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $agency = $agencies[0];

    mysqli_free_result($result);

?>

<div class="container change-info wow fadeIn" data-wow-duration="1.5s">
    <h1><i class="fas fa-user"></i> User Information</h1>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert <?php echo $_SESSION['msg_class'] ?> alert-dismissible fade show" role="alert">
            <strong><?php echo $_SESSION['msg']; ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <div class="row">
            <!-- Agency Name --> 
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="full-name">Full Name:</label>
                <input type="text" name="full-name" class="form-control" id="full-name"
                value="<?php echo $agency['agencyName'] ?>">
            </div>
            
            <!-- Agency UserName --> 
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="username">User Name:</label>
                <input type="text" name="username" class="form-control" id="username"
                value="<?php echo $agency['agencyUsername'] ?>">
            </div>

            <!-- Agency Address --> 
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" class="form-control" id="address"
                value="<?php echo $agency['agencyAddress'] ?>">
            </div>
            
            <!-- Agency Phone Number --> 
            <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                <label for="phoneNumber">Phone Number:</label>
                <input type="tel" name="phoneNumber" class="form-control" id="phoneNumber"
                value="<?php echo $agency['agencyPhoneNumber'] ?>">
            </div>

            <!-- Submit Form Group --> 
            <div class="col-12">
                <input type="submit" id="submit" name="submit" value="Commit Change" class="btn btn-primary">
                <a href="change-password.php" class="btn btn-dark">Change Your Password</a>
                <a href="product-list.php" class="btn btn-light">Back To Product List</a>
            </div>
        </div>
    </form>
</div>

<?php include("inc/footer.php") ?>