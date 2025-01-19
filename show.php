<?php

$conn = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($conn, "foodrecs");

$query = mysqli_query($conn, "SELECT acc_name, acc_email, user_age, user_gender, user_height, user_weight FROM account");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Show</title>
    <link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
    <div class="container">
        <div class="forms"> 
            <div class="form login">
                <span class="title">My Profile</span>
                <div  class="space"></div>

                    <form action="" method="post">
                        <?php
                        
                            echo "User not logged in. Please sign up and log in to your account.";
                    

                        ?>
                    </form>
                    <div class="input-field button">
                        <input type="button"  value="Create Account" onclick="location.href='register.php'">
                    </div>
                    <div class="input-field button">
                        <input type="button"  value="Log in" onclick="location.href='login.php'">
                    </div>
                    <div  class="space"></div>

                     <div class="input-field button">
                        <input type="button" value="Cancel" onclick="location.href='dashboard.php'">
                        
                    </div>
            </div>
        </div>
    </div>
</body>
</html>
