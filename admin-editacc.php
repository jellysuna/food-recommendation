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
$admin_id = $_SESSION['admin_id'];

// Fetch admin data from the database
try {
    $stmt = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $admin_name = $row['admin_name'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $new_admin_name = $_POST["admin_name"];

        // Check if the new admin username is already taken
        $check_stmt = $conn->prepare("SELECT * FROM `admin` WHERE admin_name = :new_admin_name AND admin_id != :admin_id");
        $check_stmt->bindParam(':new_admin_name', $new_admin_name, PDO::PARAM_STR);
        $check_stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            echo '<script>
                    alert("Admin username is already taken.");
                    window.location.href = "admin-editacc.php";
                  </script>';
        } else {
            // Update admin name
            $update_stmt = $conn->prepare("UPDATE `admin` SET admin_name = :new_admin_name WHERE admin_id = :admin_id");
            $update_stmt->bindParam(':new_admin_name', $new_admin_name, PDO::PARAM_STR);
            $update_stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                header("Location: admin-profile.php");
                exit();
            } else {
                echo "Error updating record.";
            }
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

    <title>Edit Account</title>
    <link rel="stylesheet" type="text/css" href="edit-user.css">
</head>

<body>
<div class="container">
    <div class="forms">
        <div class="form login">
            <span class="title">Edit Account</span>
            <form action="" method="post">
                <div class="input-field">
                    <p>Name <style></style></p> <div class="space2"></div>
                    <input type="text" required placeholder="Name" id="admin_name" name="admin_name" value="<?php echo isset($row['admin_name']) ? $row['admin_name'] : ''; ?>"><br/><br/>
                </div>

                <br>
                <div class="input-field button">
                    <input type="submit" name="submit" value="Save">
                </div>
                <div class="input-field button">
                    <input type="button" class="logout-button" value="Cancel" onclick="location.href='admin-profile.php'">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="jstry.js"></script>

</body>
</html>
