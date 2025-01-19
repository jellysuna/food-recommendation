<?php

session_start();
$admin_id = $_SESSION['admin_id'];

// Fetch admin data from the database
$conn = mysqli_connect("localhost", "root", "", "foodrecs");
$select_query = "SELECT * FROM `admin` WHERE admin_id = $admin_id";
$result = mysqli_query($conn, $select_query);
$row = mysqli_fetch_assoc($result);

$admin_name = $row['admin_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_admin_name = mysqli_real_escape_string($conn, $_POST["admin_name"]);

    // Check if the new admin username is already taken
    $check_query = "SELECT * FROM `admin` WHERE admin_name = '$new_admin_name' AND admin_id != $admin_id";
    $check_result = mysqli_query($conn, $check_query);
    $num_rows = mysqli_num_rows($check_result);

    if ($num_rows > 0) {
        echo '<script>
                    alert("Admin username is already taken.");
                    window.location.href = "admin-editacc.php"; // Redirect to the registration page
                  </script>';
    } else {
        $update_query = "UPDATE `admin` SET admin_name = '$new_admin_name' WHERE admin_id = $admin_id";

        if (mysqli_query($conn, $update_query)) {
            header("Location: admin-profile.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
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

    <title>Edit</title>
    <link rel="stylesheet" type="text/css" href="editacc1.css">
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
