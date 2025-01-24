<?php
require 'config.php';
$invalid=""; //Variable to Store error message;

if (isset($_POST["submit"])) {
    if (empty($_POST["admin_name"]) || empty($_POST["admin_email"]) || empty($_POST["admin_password"])) {
        $invalid = "Must fill all areas";
    } else {
        $username = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        $password = password_hash($_POST['admin_password'], PASSWORD_BCRYPT); // Hash the password

        try {
            // Check if email or username already exists in the database
            $duplicate_query = "SELECT * FROM `admin` WHERE `admin_name` = :admin_name OR `admin_email` = :admin_email";
            $stmt = $conn->prepare($duplicate_query);
            $stmt->bindParam(':admin_name', $username, PDO::PARAM_STR);
            $stmt->bindParam(':admin_email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo '<script>
                        alert("Email or username is already taken.");
                        window.location.href = "admin-register.php"; // Redirect to the registration page
                      </script>';
            } else {
                // Insert the new admin into the database
                $register_query = "INSERT INTO `admin`(`admin_name`, `admin_email`, `admin_password`) VALUES (:admin_name, :admin_email, :admin_password)";
                $stmt = $conn->prepare($register_query);
                $stmt->bindParam(':admin_name', $username, PDO::PARAM_STR);
                $stmt->bindParam(':admin_email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':admin_password', $password, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    header("Location: adminlogin.php");
                } else {
                    echo "Registration failed";
                }
            }
        } catch (PDOException $ex) {
            echo "Error: " . $ex->getMessage();
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
    <link rel="stylesheet" type="text/css" href="login.css">
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