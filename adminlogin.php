<?php
session_start();
$invalid = ""; 
require 'config.php';

if (isset($_POST["submit"])) {
    if (empty($_POST["admin_name"]) || empty($_POST['admin_password'])) {
        $invalid = "Incorrect";
    } else {
        $user = $_POST['admin_name'];
        $pass = $_POST['admin_password'];

        try {
            // Retrieve the hashed password from the database using prepared statements
            $stmt = $conn->prepare("SELECT * FROM `admin` WHERE admin_name = :user");
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashed_password = $row['admin_password'];

                if (password_verify($pass, $hashed_password)) {
                    $_SESSION['admin_id'] = $row['admin_id'];
                    header("Location: admin-page.php");
                    exit();
                } else {
                    $invalid = "Incorrect";
                    echo "<script>alert('Fail: Incorrect password');</script>";
                }
            } else {
                $invalid = "Incorrect";
                echo "<script>alert('Fail: No such user');</script>";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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

    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
<div class="containers">
    <p class="logo-text">Login as Admin</p>
    <div  class="space"></div>
    <a href="chooseuser.php"> 
        <img src="img/chef2.png" alt="Logo" class="logo">
    </a>
</div>
<div  class="space"></div>

<div class="container">
    <div class="forms"> 
        <div class="form login">
            <span class="title">Log in</span>
            <form action="" method="post" >
                <div class="input-field">
                    <input type="text" required placeholder="Enter your username" id="admin_name" name="admin_name"><br/><br/>
                    <i class="uil uil-user icon"></i>
                </div>
                <div class="input-field">
                    <input type="password" required placeholder="Enter your password" id="admin_password" name="admin_password"><br/><br/>
                    <i class="uil uil-lock icon"></i>
                    <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
                </div>
                <div class="checkbox-text">
                    <span></span>
                    <a href="#" class="text">Forgot password?</a>
                </div>
                <div class="input-field button">
                    <input type="submit" value="Log in" name="submit">
                </div>
                <div class="login-signup">
                    <span class="text">Don't have an account?
                        <a href="admin-register.php" class="text signup-link">Sign up </a>
                    </span>
                </div>
                <span><?php echo $invalid; ?></span>
            </form>
        </div>
    </div> 
</div>

<script src="jstry.js"></script> 
<script>
    document.addEventListener("DOMContentLoaded", function () {
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
    });
</script>

</body>
</html>