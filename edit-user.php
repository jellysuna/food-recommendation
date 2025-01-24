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

$acc_id = $_SESSION['acc_id'];

try {
    // Fetch user data from the database
    $stmt = $conn->prepare("SELECT * FROM account WHERE acc_id = :acc_id");
    $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "User not found.";
        exit();
    }

    // Assign fetched data to variables
    $acc_name = $row['acc_name'];
    $user_age = $row['user_age'];
    $user_gender = $row['user_gender'];
    $user_weight = $row['user_weight'];
    $user_height = $row['user_height'];
    $user_activity_level = $row['activity_level'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the updated values from the form
        $new_acc_name = $_POST["acc_name"];
        $new_user_age = empty($_POST["user_age"]) ? null : $_POST["user_age"];
        $new_user_gender = empty($_POST["user_gender"]) ? null : $_POST["user_gender"];
        $new_user_weight = empty($_POST["user_weight"]) ? null : $_POST["user_weight"];
        $new_user_height = empty($_POST["user_height"]) ? null : $_POST["user_height"];
        $new_user_activity_level = empty($_POST["activity_level"]) ? null : $_POST["activity_level"];

        // Check if the new username is already taken
        $check_stmt = $conn->prepare("SELECT * FROM account WHERE acc_name = :acc_name AND acc_id != :acc_id");
        $check_stmt->bindParam(':acc_name', $new_acc_name);
        $check_stmt->bindParam(':acc_id', $acc_id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            echo '<script>
                    alert("Username is already taken.");
                    window.location.href = "editacc.php";
                  </script>';
        } else {
            // Update user data
            $update_stmt = $conn->prepare("UPDATE account SET acc_name = :acc_name, user_age = :user_age, user_gender = :user_gender, user_weight = :user_weight, user_height = :user_height, activity_level = :activity_level WHERE acc_id = :acc_id");
            $update_stmt->bindParam(':acc_name', $new_acc_name);
            $update_stmt->bindParam(':user_age', $new_user_age, PDO::PARAM_INT);
            $update_stmt->bindParam(':user_gender', $new_user_gender);
            $update_stmt->bindParam(':user_weight', $new_user_weight, PDO::PARAM_INT);
            $update_stmt->bindParam(':user_height', $new_user_height, PDO::PARAM_INT);
            $update_stmt->bindParam(':activity_level', $new_user_activity_level);
            $update_stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);

            if ($update_stmt->execute()) {
                calculateUserCalorie($conn, $acc_id);
                header("Location: user-profile.php");
                exit();
            } else {
                echo "Error updating record.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
function calculateBMR($gender, $weight, $height, $age)
{
    if ($gender === 'Male') {
        return 66.5 + (13.8 * $weight) + (5 * $height) - (6.8 * $age);
    } elseif ($gender === 'Female') {
        return 655.1 + (9.6 * $weight) + (1.9 * $height) - (4.7 * $age);
    } else {
        return 0; 
    }
}

function calculateUserCalorie($conn, $acc_id) {
    try {
        // Fetch user details needed for calculation
        $stmt = $conn->prepare("SELECT user_age, user_gender, user_weight, user_height, activity_level FROM account WHERE acc_id = :acc_id");
        $stmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check if all required fields are present
            if (
                isset($user['user_age']) &&
                isset($user['user_gender']) &&
                isset($user['user_weight']) &&
                isset($user['user_height']) &&
                isset($user['activity_level'])
            ) {
                // Calculate BMR using the Harris-Benedict equation
                $bmr = calculateBMR($user['user_gender'], $user['user_weight'], $user['user_height'], $user['user_age']);

                // Map activity levels to multipliers
                $activityMultipliers = [
                    'inactive' => 1.2,
                    'lightly_active' => 1.375,
                    'moderately_active' => 1.55,
                    'very_active' => 1.725,
                ];

                // Get the activity multiplier for the user's activity level
                $activityMultiplier = $activityMultipliers[$user['activity_level']]; // Default to 1.2 if not found

                // Calculate TDEE
                $tdee = $bmr * $activityMultiplier;

                // Update TDEE in the database
                $updateStmt = $conn->prepare("UPDATE account SET user_calorie = :tdee WHERE acc_id = :acc_id");
                $updateStmt->bindParam(':tdee', $tdee, PDO::PARAM_INT);
                $updateStmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                // If any required attribute is missing, set user_calorie to 0
                $updateStmt = $conn->prepare("UPDATE account SET user_calorie = 0 WHERE acc_id = :acc_id");
                $updateStmt->bindParam(':acc_id', $acc_id, PDO::PARAM_INT);
                $updateStmt->execute();
            }
        }
    } catch (PDOException $e) {
        // Handle any database-related errors
        echo "Error calculating user calorie: " . $e->getMessage();
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

    <title>Edit</title>
    <link rel="stylesheet" type="text/css" href="edit-user.css">
</head>

<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Edit Account</span>
                <form action="" method="post">
                    <div class="input-field">
                        <p>Name <style></style>
                        </p>
                        <div class="space2"></div>
                        <input type="text" required placeholder="Name" id="acc_name" name="acc_name"
                            value="<?php echo isset($row['acc_name']) ? $row['acc_name'] : ''; ?>"><br /><br />
                    </div>

                    <div class="input-field">
                        <p>Age <style></style>
                        </p>
                        <div class="space"></div>
                        <input type="text" placeholder="Age" id="user_age" name="user_age"
                            value="<?php echo isset($row['user_age']) ? $row['user_age'] : ''; ?>"><br /><br />
                    </div>

                    <div class="input-field">
                        <p>Weight <style></style>
                        </p>
                        <input type="text" placeholder="Weight (in kg)" id="user_weight" name="user_weight"
                            value="<?php echo isset($row['user_weight']) ? $row['user_weight'] : ''; ?>"><br /><br />
                    </div>
                    
                    <div class="input-field">
                        <p>Height <style></style>
                        </p>
                        <input type="text" placeholder="Height (in cm)" id="user_height" name="user_height"
                            value="<?php echo isset($row['user_height']) ? $row['user_height'] : ''; ?>"><br /><br />
                    </div>
                    <div class="input-field">
                        <p>Gender</p>
                        <select name="user_gender" id="user_gender">
                            <option value="Male" <?php echo ($row['user_gender'] == 'Male') ? 'selected' : ''; ?>>Male
                            </option>
                            <option value="Female" <?php echo ($row['user_gender'] == 'Female') ? 'selected' : ''; ?>>
                                Female</option>
                        </select>
                        <div class="space"> </div>
                        <p>Activity Level</p>
                        <select name="activity_level" id="activity_level">
                            <option value="inactive" <?php echo ($row['activity_level'] == 'inactive') ? 'selected' : ''; ?>>Inactive (Little to no exercise)</option>
                            <option value="lightly_active" <?php echo ($row['activity_level'] == 'lightly_active') ? 'selected' : ''; ?>>Lightly Active (2-3 times a week)</option>
                            <option value="moderately_active" <?php echo ($row['activity_level'] == 'moderately_active') ? 'selected' : ''; ?>>Moderately Active (30 mins per day)</option>
                            <option value="very_active" <?php echo ($row['activity_level'] == 'very_active') ? 'selected' : ''; ?>>Very Active (1-2 hours per day)</option>
                        </select>
                    </div>

                    <br>
                    <div class="input-field button">
                        <input type="submit" name="submit" value="Save">
                    </div>
                    <div class="input-field button">
                        <input type="button" class="logout-button" value="Cancel" onclick="location.href='user-profile.php'">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="jstry.js"></script>

</body>
</html>