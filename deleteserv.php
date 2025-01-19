<?php
    session_start();
    $success = "";

    $conn = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($conn, "foodrecs");

    if (isset($_POST['submit'])) {
        $email = $_POST['acc_email'];
        $password = $_POST['acc_password'];

        // Retrieve the account ID based on email
        $accountQuery = mysqli_query($conn, "SELECT acc_id, acc_password FROM account WHERE acc_email='$email'");
        $accountData = mysqli_fetch_assoc($accountQuery);

        if ($accountData) {
            $hashedPassword = $accountData['acc_password'];
            $accountId = $accountData['acc_id'];

            // Check if the entered password is correct
            if (password_verify($password, $hashedPassword)) {
                // Delete from account table using acc_id
                $accountDeleteQuery = "DELETE FROM account WHERE acc_id='$accountId'";
                $accountResult = mysqli_query($conn, $accountDeleteQuery);

                // $profileDeleteQuery = "DELETE FROM user_profile WHERE acc_id='$accountId'";
                // $profileResult = mysqli_query($conn, $profileDeleteQuery);

                if ($accountResult) {
                    echo "<script>
                        alert('Account deleted successfully!');
                        window.location.href = 'chooseuser.php';
                      </script>";
                } else {
                    echo "Error deleting record: " . $conn->error;
                }
            } else {
                $success = "Please enter the correct information.";
            }
        } else {
            $success = "Account not found.";
        }

        $conn->close();
    }
?>
