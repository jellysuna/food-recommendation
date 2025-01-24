<?php
session_start();
$success = "";

// Check if the user is logged in
if (!isset($_SESSION['acc_id'])) {
    header("Location: login.php");
    exit();
}
  
if (isset($_POST['logout'])) {
session_destroy();
unset($_SESSION['acc_id']);
header("Location: login.php");
}

// Include the database configuration 
require 'config.php';

if (isset($_POST['submit'])) {
    $email = $_POST['acc_email'];
    $password = $_POST['acc_password'];

    try {
        // Retrieve the account ID and hashed password based on email
        $stmt = $conn->prepare("SELECT acc_id, acc_password FROM account WHERE acc_email = :email");
        $stmt->execute(['email' => $email]);
        $accountData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($accountData) {
            $hashedPassword = $accountData['acc_password'];
            $accountId = $accountData['acc_id'];

            // Check if the entered password is correct
            if (password_verify($password, $hashedPassword)) {
                // Delete the account using acc_id
                $deleteStmt = $conn->prepare("DELETE FROM account WHERE acc_id = :accountId");
                $deleteStmt->execute(['accountId' => $accountId]);

                if ($deleteStmt->rowCount() > 0) {
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
        echo "Database error: " . $e->getMessage();
    }
}
?>
