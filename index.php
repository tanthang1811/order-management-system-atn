<?php include("inc/header.php") ?>
<?php require "config/database.php"?>

<?php
    session_start();
    $errors = [];

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT agencyID FROM agency WHERE agencyUsername = '$username' and agencyPassword = '$password'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $userid = $row['agencyID'];
    
        $count = mysqli_num_rows($result);

        if($count == 1) {
            $_SESSION['login_agency_id'] = $userid;
            $_SESSION['login_agency'] = $username;
            
            header("location: product-list.php");
        } else {
            $errors[] = "Wrong Credentials";
        }
    }

?>

<div class="login-container">
    <!-- Login Banner -->
    <div class="login-banner wow fadeInLeft" data-wow-duration="2s" >
        <img src="img/login-bg.jpg" alt="" class="">
        <div class="login-content centralize">
            <h1 class="wow fadeInUp" data-wow-duration="2.5s">Welcome to the <br> Order Management System</h1>
        </div>
    </div>
    
    <!-- Login Form -->
    <div class="login-form wow fadeInRight" data-wow-duration="2s">
        <h2><i class="fas fa-sign-in-alt"></i> Login</h2>

        <!-- Error Dismissal -->
        <?php if(!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $error; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
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
                <input type="submit" value="Login" id="submit" name="submit" class="btn btn-primary" />
                <input type="reset" value="Reset" id="reset" name="reset" class="btn btn-dark" />
                <a href="registration.php" class="btn btn-light">Don't have an account?</a>
            </div>

            <!-- Utilities Form Group -->
            <div class="form-group">
                <a href="change-password.php">Change your password</a>
            </div>
        </form>
    </div>
</div>

<?php include("inc/footer.php") ?>