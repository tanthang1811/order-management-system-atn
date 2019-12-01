<?php include("inc/header.php") ?>
<?php require "config/database.php"?>

<?php
    session_start();
    $msg = [];
    $msg_class = "";
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $newPassword = $_POST['new-password'];

        $sql = "SELECT agencyID FROM agency WHERE agencyUsername = '$username' and agencyPassword = '$password'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $userid = $row['agencyID'];
    
        $count = mysqli_num_rows($result);

        if($count == 1) {
            $sql = "UPDATE agency 
            SET agencyPassword='$newPassword' 
            WHERE agencyID=$userid";
            $result = mysqli_query($conn,$sql);
            $msg[] = "You have successfully change your password";
            $msg_class = "alert-success";
        } else {
            $msg[] = "Wrong Credentials";
            $msg_class = "alert-danger";
        }
    }
    
?>

<div class="login-container">
    
    <!-- Change Password Form -->
    <div class="login-form change-password-form wow fadeInLeft" data-wow-duration="2s" >
        <h2><i class="fas fa-key"></i> CHANGE PASSWORD</h2>

        <!-- Error Dismissal -->
        <?php if(!empty($msg)): ?>
        <?php foreach ($msg as $error): ?>
            <div class="alert alert-dismissible fade show <?php echo $msg_class; ?>" role="alert">
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
                <label for="password">Old Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Old Password" />
            </div>

            <!-- Password Form Group -->
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" class="form-control" placeholder="New Password" />
            </div>

            <!-- Submit Form Group -->
            <div class="form-group">
                <input type="submit" value="Commit Change" id="submit" name="submit" class="btn btn-primary" />
                <input type="reset" value="Reset" id="reset" name="reset" class="btn btn-dark" />
                <a href="index.php" class="btn btn-light">Back To Login Page</a>
            </div>
        </form>
    </div>

    <!-- Change Password Banner -->
    <div class="login-banner wow fadeInRight" data-wow-duration="2s" >
        <img src="img/login-bg.jpg" alt="" class="">
        <div class="login-content centralize">
            <h1 class="wow fadeInUp" data-wow-duration="2.5s" >REMEMBER TO <br/> CHANGE YOUR PASSWORD WISELY</h1>
        </div>
    </div>
</div>

<?php include("inc/footer.php") ?>

<?php include("inc/footer.php") ?>