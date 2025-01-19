<?php
session_start();
$invalid = ""; 

if (isset($_POST["submit"])) {
    if (empty($_POST["acc_name"]) || empty($_POST['acc_password'])) {
        $invalid = "Incorrect";
    } else {
        $user = $_POST['acc_name'];
        $pass = $_POST['acc_password'];
        $conn = mysqli_connect("localhost", "root", "");
        $db = mysqli_select_db($conn, "foodrecs"); 

        // Retrieve the hashed password from the database
        $query = mysqli_query($conn, "SELECT * FROM account WHERE acc_name='$user'");
        $rows = mysqli_num_rows($query);

        if ($rows == 1) {
            $row = mysqli_fetch_assoc($query);
            $hashed_password = $row['acc_password'];

            if (password_verify($pass, $hashed_password)) {
                $_SESSION['acc_id'] = $row['acc_id']; // Set the account ID in the session
                header("Location: login-access.php"); // Redirect to the profile page
                exit(); 
            } else {
                $invalid = "Incorrect";
            }
        } else {
            $invalid = "Incorrect";
        }

        mysqli_close($conn);
    }
}
?>
