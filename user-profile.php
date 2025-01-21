<?php
session_start();

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

require 'config.php';

$conn = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($conn, "foodrecs");

$query = mysqli_query($conn, "SELECT acc_name, acc_email, user_age, user_gender, user_height, user_weight FROM account");

function formatActivityLevel($level)
{
    return ucwords(str_replace("_", " ", $level));
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Profile</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #b7adde;
        }

        .login {
            width: 500px;
            border-radius: 10px;
            background-color: #fff;
            padding: 20px 50px 45px;
            margin: 30px auto;
            margin-top: 60px;
        }

        .form .button {
            margin-top: 35px;
        }

        .form .button input {
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 500;
            size: 100px;
            letter-spacing: 1px;
            border-radius: 6px;
            background-color: #a99cdf;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 30px;
            width: 150px;
        }

        .button input:hover {
            background-color: #b7adde;
        }

        .form .input-field {
            position: relative;
            height: 1px;
            width: 100%;
            margin-top: 30px;
        }

        .container .form .title {
            position: relative;
            font-size: 27px;
            font-weight: 600;
        }

        .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #b7adde;
            border-radius: 25px;
        }

        .space {
            margin-top: 40px;
            margin-right: 60px;
        }

        nav {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 20px;
        }

        .logo a {
            font-size: 28px;
            text-decoration: none;
            font-family: cookie;
            font-weight: bold;
            color: #433e58;
        }

        .logo img {
            max-height: 28px;
        }

        .container1 {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            margin-top: 30px;
            margin-left: 60px;
            width: 100%;
        }

        .container {
            margin-top: 40px;
            margin-left: 300px;
        }

        .label {
            font-weight: 550;
            color: #433e58;
        }
    </style>
</head>

<body>
<div class="container1">
    <nav>
        <div class="logo">
            <a href="login-access.php">
                <img src="img/0.1.png" alt="Logo">
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="forms"> 
            <div class="form login">
                <span class="title">My Profile</span>
                <div  class="space"></div>

                <form action="" method="">
                    <?php
                    if (isset($_SESSION['acc_id'])) {
                        $acc_id = $_SESSION['acc_id'];
                        $query = mysqli_query($conn, "SELECT acc_name, acc_email, user_age, user_gender, user_height, user_weight, activity_level FROM account WHERE acc_id = $acc_id");

                        // Check if a result was found
                        if ($row = mysqli_fetch_assoc($query)) {
                            $username = $row['acc_name'];
                            $email = $row['acc_email'];
                            $age = $row['user_age'];
                            $gender = $row['user_gender'];
                            $height = $row['user_height'];
                            $weight = $row['user_weight'];
                            $user_activity_level = $row['activity_level'];
                            $formatted_activity_level = formatActivityLevel($user_activity_level);
                            echo "<span class='label'>Name:</span> " . $username . "<br/>";
                            echo "<span class='label'>Email:</span> " . $email . "<br/>";
                            echo "<span class='label'>Age:</span> " . $age . "<br/>";
                            echo "<span class='label'>Gender:</span> " . $gender . "<br/>";
                            echo "<span class='label'>Weight:</span> " . $weight . "<br/>";
                            echo "<span class='label'>Height:</span> " . $height . "<br/>";
                            echo "<span class='label'>Activity Level:</span> " . $formatted_activity_level . "<br/><br/><br/>";

                        } else {
                            echo "User information not found";
                        }
                    } else {
                        echo "Not logged in";
                    }

                    ?>
                </form>
                <div class="input-field button">
                    <input type="button"  value="Edit Account" onclick="location.href='editacc.php'">
                </div>
                <div class="input-field button">
                    <input type="button"  value="Delete Account" onclick="location.href='delete.php'">
                </div>
                <div  class="space"></div>

                <div class="input-field button">
                    <form method="post">
                        <input type="submit" name="logout"value="Log out"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
