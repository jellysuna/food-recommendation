<?php
require 'config.php';

$invalid = ""; 
if (isset($_POST["submit"])) {
    if (empty($_POST["acc_name"]) || empty($_POST["acc_email"]) || empty($_POST["acc_password"])) {
        $invalid = "Must fill all areas";
    } else {
        $username = $_POST['acc_name'];
        $email = $_POST['acc_email'];
        $password = password_hash($_POST['acc_password'], PASSWORD_BCRYPT); // Hash the password

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $invalid = "Invalid email format";
        } else {
            // Check if email or username already exists in the database
            $duplicate_query = "SELECT * FROM `account` WHERE `acc_name` = '$username' OR `acc_email` = '$email'";
            $duplicate_result = mysqli_query($conn, $duplicate_query);

            if (mysqli_num_rows($duplicate_result) > 0) {
                echo '<script>
                        alert("Email or username is already taken.");
                        window.location.href = "register.php"; // Redirect to the registration page
                      </script>';
            } else {
                $register_query = "INSERT INTO `account`(`acc_name`, `acc_email`, `acc_password`) VALUES ('$username', '$email', '$password')";

                try {
                    $register_result = mysqli_query($conn, $register_query);
                    if ($register_result && mysqli_affected_rows($conn) > 0) {
                        header("Location: login.php");
                    } else {
                        echo "Registration failed";
                    }
                } catch (Exception $ex) {
                    echo "error" . $ex->getMessage();
                }
            }
        }
    }
}

?> 