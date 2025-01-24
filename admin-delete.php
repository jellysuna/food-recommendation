<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['admin_id'])) {
        header("Location: chooseuser.php");
        exit();
    }
    
    if (isset($_POST['logout'])) {
        session_destroy();
        unset($_SESSION['admin_id']);
        header("Location: chooseuser.php");
    }
    
    require 'config.php';
    $success = "";

    if (isset($_POST['submit'])) {
        $email = $_POST['admin_email'];
        $password = $_POST['admin_password'];
    
        try {
            // Retrieve the account data based on email
            $stmt = $conn->prepare("SELECT admin_id, admin_password FROM `admin` WHERE admin_email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $accountData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($accountData) {
                $hashedPassword = $accountData['admin_password'];
                $adminID = $accountData['admin_id'];
    
                // Check if the entered password is correct
                if (password_verify($password, $hashedPassword)) {
                    // Delete from the admin table using admin_id
                    $deleteStmt = $conn->prepare("DELETE FROM `admin` WHERE admin_id = :admin_id");
                    $deleteStmt->bindParam(':admin_id', $adminID, PDO::PARAM_INT);
    
                    if ($deleteStmt->execute()) {
                        echo "<script>
                            alert('Account deleted successfully!');
                            window.location.href = 'chooseuser.php';
                          </script>";
                    } else {
                        echo "Error deleting record.";
                    }
                } else {
                    $success = "Please enter the correct information.";
                }
            } else {
                $success = "Account not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
         <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Delete Admin</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>

<div class="container">
    <div class="forms"> 
        <div class="form login">
            <span class="title">Delete Account</span>
            <form action="" method="post" >
                <div class="input-field">
                        <input type="text" required placeholder="Enter your email" id="admin_email" name="admin_email"><br/><br/>
                        <i class="uil uil-envelope icon"></i>
                </div>
                <div class="input-field">
                        <input type="password" required placeholder="Enter your password" id="admin_password" name="admin_password"><br/><br/>
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
                        <div  class="space2"></div>
                </div>

                <div class="input-field button">
                    <input type="submit" name="submit" value="Delete my account">
                </div>
                <div class="input-field button">
                    <input type="button" class="logout-button" value="Cancel" onclick="location.href='admin-profile.php'">
                </div>
                <span><?php echo $success; ?></span>
            </form>
        </div>
    </div>
</div>

<script src="jstry.js"></script> 
<script>
    function myFunction() {
        var x = document.getElementById("acc_password");
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