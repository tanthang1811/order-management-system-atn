<?php include("inc/header.php")?>
<?php require "config/database.php" ?>

<?php
    $msg = "";
    $msg_class = "";

    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "INSERT INTO agency
        (agencyName, agencyAddress, agencyPhoneNumber, agencyUsername, agencyPassword) 
         VALUES 
         ('$name', '$address', '$tel', '$username', '$password')";

        $result = mysqli_query($conn, $sql);

        if ($result){
            $msg = "You have successfully create a new account";
            $msg_class = "alert-success";
        } else {
            $msg = "Oops something went wrong";
            $msg_class = "alert-danger";
        }
    }
?>

<div class="regis-container login-container">
    <!-- Registration Banner -->
    <div class="login-banner wow fadeInLeft" data-wow-duration="2s">
        <img src="img/login-bg.jpg" alt="" class="w-100 h-100">
        <div class="login-content centralize">
            <h1 class="wow fadeInUp" data-wow-duration="2.5s">Welcome to the <br> Order Management System</h1>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="login-form wow fadeInRight" data-wow-duration="2s">
        <h2><i class="fas fa-sign-in-alt"></i> Registration</h2>

        <!-- Notification Dismissal -->
        <?php if(!empty($msg_class)): ?>
        <div class="alert <?php echo $msg_class?> alert-dismissible fade show" role="alert">
            <strong><?php echo $msg; ?></strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <!-- Name Form Group -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Name" />
            </div>

            <!-- Address Form Group -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-control" placeholder="Address" />
            </div>

            <!-- Address Form Group -->
            <div class="form-group">
                <label for="tel">Telephone:</label>
                <input type="tel" id="tel" name="tel" class="form-control" placeholder="Telephone" />
            </div>

            <!-- Username Form Group -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" />
            </div>

            <!-- Password Form Group -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
            </div>

            <!-- Submit Form Group -->
            <div class="form-group">
                <input type="submit" value="Sign Up" id="submit" name="submit" class="btn btn-primary" />
                <input type="reset" value="Reset" id="reset" name="reset" class="btn btn-dark" />
                <a href="index.php" class="btn btn-light">
                    Go Back To Login
                </a>
            </div>

        </form>
    </div>
</div>

<?php include("inc/footer.php")?>