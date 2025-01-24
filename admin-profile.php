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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Profile</title>

    <style>
        /* ===== Google Font Import - Poppins ===== */
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
    </style>
</head>

<body>
<div class="container1">
    <nav>
        <div class="logo">
            <a href="admin-page.php">
                <img src="img/0.1.png" alt="Logo">
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="forms"> 
            <div class="form login">
                <span class="title">My Profile</span>
                <div class="space"></div>

                <form action="" method="post">
                    <?php
                    if (isset($_SESSION['admin_id'])) {
                        $admin_id = $_SESSION['admin_id'];

                        try {
                            // Use prepared statements to prevent SQL injection
                            $stmt = $conn->prepare("SELECT admin_name, admin_email FROM `admin` WHERE admin_id = :admin_id");
                            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
                            $stmt->execute();

                            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $username = $row['admin_name'];
                                $email = $row['admin_email'];
                                echo "Name: " . htmlspecialchars($username) . "<br/>";
                                echo "Email: " . htmlspecialchars($email) . "<br/><br><br>";
                            } else {
                                echo "User information not found";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                    } else {
                        echo "Not logged in";
                    }
                    ?>
                </form>

                <div class="input-field button">
                    <input type="button" value="Edit Account" onclick="location.href='admin-editacc.php'">
                </div>
                <div class="input-field button">
                    <input type="button" value="Delete Account" onclick="location.href='admin-delete.php'">
                </div>
                <div class="space"></div>
                <div class="input-field button">
                    <form method="post">
                        <input type="submit" name="logout" value="Log Out" class="btn"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
