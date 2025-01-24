<?php
session_start();
$invalid = "";
require 'config.php';

if (isset($_POST["submit"])) {
    if (empty($_POST["acc_name"]) || empty($_POST['acc_password'])) {
        $invalid = "Incorrect";
    } else {
        $user = $_POST['acc_name'];
        $pass = $_POST['acc_password'];

        try {
            // Retrieve the hashed password from the database using prepared statements
            $stmt = $conn->prepare("SELECT * FROM `account` WHERE acc_name = :user");
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashed_password = $row['acc_password'];

                if (password_verify($pass, $hashed_password)) {
                    $_SESSION['acc_id'] = $row['acc_id'];
                    header("Location: login-access.php");
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
