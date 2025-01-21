<?php

$invalid=""; //Variable to Store error message;

if(isset($_POST["submit"])){
    if(empty($_POST["admin_name"]) || empty($_POST["admin_email"]) || empty($_POST["admin_password"])) {
        $invalid = "Must fill all areas";
    } else {
        $conn = mysqli_connect ("localhost", "root", "");
        $db = mysqli_select_db($conn, "foodrecs"); 

        $username=$_POST['admin_name'];
        $email=$_POST['admin_email'];
        $password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT); // Hash the password

       //check if email or username already existed in database
       $duplicate_query = "SELECT * FROM `admin` WHERE `admin_name` = '$username' OR `admin_email` = '$email'";
       $duplicate_result = mysqli_query($conn, $duplicate_query);

       if (mysqli_num_rows($duplicate_result) > 0) {
        echo '<script>
                alert("Email or username is already taken.");
                window.location.href = "admin-register.php"; // Redirect to the registration page
              </script>';
       } else {
            $register_query = "INSERT INTO `admin`(`admin_name`, `admin_email`, `admin_password`) VALUES ('$username', '$email', '$password')";

            try {
                $register_result = mysqli_query($conn, $register_query);
                if ($register_result && mysqli_affected_rows($conn) > 0) {
                    header("Location: adminlogin.php");
                } else {
                    echo "Registration failed";
                }
            } catch(Exception $ex) {
                echo("error".$ex->getMessage());
            }
       } 
        
    }
}

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Register </title>
    <link rel="stylesheet" type="text/css" href="login1.css">
</head>

<body>
<div class="containers">
    <p class="logo-text">Become an Admin</p>
    <div  class="space"></div>
    <a href="chooseuser.php"> 
    <img src="img/chef2.png" alt="Logo" class="logo"></a>
</div>
<div  class="space"></div>

<div class="container">
    <div class="forms"> 
        <div class="form login">
            <span class="title">Join dishcover now</span>
            <form action="" method="post" >
                <div class="input-field">
                    <input type="text" required placeholder="Enter your username" id="admin_name" name="admin_name"><br/><br/>
                    <i class="uil uil-user icon"></i>
                </div>
                <div class="input-field">
                    <input type="text" name="admin_email" required placeholder="Enter your email" required><br/><br/>
                    <i class="uil uil-envelope icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" required placeholder="Create a password" id="admin_password" name="admin_password"><br/><br/>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
                </div>
                <div class="checkbox-text">
                    <div class="checkbox-content">
                        <input type="checkbox" id="termCon">
                        <label for="termCon" class="text">I accepted all terms and conditions</label>
                    </div>
                </div>
                <div class="input-field button">
                    <input type="submit" name="submit" value="Sign up">
                </div>
                <div class="login-signup">
                    <span class="text">Already have an account?
                        <a href="adminlogin.php" class="text login-link">Log in</a>
                    </span>
                </div>
                <span><?php echo $invalid; ?></span>
            </form>
        </div>
    </div>
</div>

<script src="jstry.js"></script> 
<script>
    function myFunction() {
    var x = document.getElementById("admin_password");
    var icon = document.getElementById("showHideIcon");
    
    if (x.type === "password") {
        x.type = "text";
        icon.classList.add("uil-eye");
        icon.classList.remove("uil-eye-slash");
    } else {
        x.type = "password";
        icon.classList.remove("uil-eye");
        icon.classList.add("uil-eye-slash");
    }
    }
</script>
   
</body>
</html>