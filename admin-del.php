<?php
    session_start();
    $success = "";

    $conn = mysqli_connect("localhost", "root", "");
    $db = mysqli_select_db($conn, "foodrecs");

    if (isset($_POST['submit'])) {
        $email = $_POST['admin_email'];
        $password = $_POST['admin_password'];

        // Retrieve the account ID based on email
        $accountQuery = mysqli_query($conn, "SELECT admin_id, admin_password FROM `admin` WHERE admin_email='$email'");
        $accountData = mysqli_fetch_assoc($accountQuery);

        if ($accountData) {
            $hashedPassword = $accountData['admin_password'];
            $adminID = $accountData['admin_id'];

            // Check if the entered password is correct
            if (password_verify($password, $hashedPassword)) {
                // Delete from account table using acc_id
                $accountDeleteQuery = "DELETE FROM `admin` WHERE admin_id='$adminID'";
                $accountResult = mysqli_query($conn, $accountDeleteQuery);

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

<!doctype html
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
         <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

        <title>Delete</title>
        <link rel="stylesheet" type="text/css" href="login1.css">
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