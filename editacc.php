<?php

session_start();
$acc_id = $_SESSION['acc_id'];

// Fetch user data from the database
$conn = mysqli_connect("localhost", "root", "", "foodrecs");
$select_query = "SELECT * FROM account WHERE acc_id = $acc_id";
$result = mysqli_query($conn, $select_query);
$row = mysqli_fetch_assoc($result);

// Assign fetched data to variables
$acc_name = $row['acc_name'];
$user_age = $row['user_age'];
$user_gender = $row['user_gender'];
$user_weight = $row['user_weight'];
$user_height = $row['user_height'];
$user_activity_level = $row['activity_level'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated values from the form
    $new_acc_name = mysqli_real_escape_string($conn, $_POST["acc_name"]);

    // Check if the new username is already taken
    $check_query = "SELECT * FROM account WHERE acc_name = '$new_acc_name' AND acc_id != $acc_id";
    $check_result = mysqli_query($conn, $check_query);
    $num_rows = mysqli_num_rows($check_result);

    if ($num_rows > 0) {
        echo '<script>
                    alert("Username is already taken.");
                    window.location.href = "editacc.php"; // Redirect to the registration page
                  </script>';
    } else {
        $new_user_age = empty($_POST["user_age"]) ? "NULL" : mysqli_real_escape_string($conn, $_POST["user_age"]);
        $new_user_gender = empty($_POST["user_gender"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["user_gender"]) . "'";
        $new_user_weight = empty($_POST["user_weight"]) ? "NULL" : mysqli_real_escape_string($conn, $_POST["user_weight"]);
        $new_user_height = empty($_POST["user_height"]) ? "NULL" : mysqli_real_escape_string($conn, $_POST["user_height"]);
        $new_user_activity_level = empty($_POST["activity_level"]) ? "NULL" : "'" . mysqli_real_escape_string($conn, $_POST["activity_level"]) . "'";
        $update_query = "UPDATE account SET acc_name = '$new_acc_name', user_age = $new_user_age, user_gender = $new_user_gender, user_weight = $new_user_weight, user_height = $new_user_height, activity_level = $new_user_activity_level WHERE acc_id = $acc_id";

        if (mysqli_query($conn, $update_query)) {
            calculateUserCalorie($conn, $acc_id);
            header("Location: check.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
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

function calculateUserCalorie($conn, $acc_id)
{
    $query = mysqli_prepare($conn, "SELECT user_age, user_gender, user_weight, user_height, activity_level FROM `account` WHERE acc_id = ?");
    mysqli_stmt_bind_param($query, 'i', $acc_id);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
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

            // Use the activity multiplier directly
            $activityMultiplier = $activityMultipliers[$user['activity_level']];

            // Calculate TDEE
            $tdee = $bmr * $activityMultiplier;

            // Store the TDEE in the database
            $updateTDEEQuery = mysqli_prepare($conn, "UPDATE account SET user_calorie = ? WHERE acc_id = ?");
            mysqli_stmt_bind_param($updateTDEEQuery, 'ii', $tdee, $acc_id);
            mysqli_stmt_execute($updateTDEEQuery);
        } else {
            // If any required attribute is missing, set user_calorie to 0
            $updateTDEEQuery = mysqli_prepare($conn, "UPDATE account SET user_calorie = 0 WHERE acc_id = ?");
            mysqli_stmt_bind_param($updateTDEEQuery, 'i', $acc_id);
            mysqli_stmt_execute($updateTDEEQuery);
        }
    }
}


?>

<!doctype html <html>

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
                        <input type="button" class="logout-button" value="Cancel" onclick="location.href='check.php'">
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="jstry.js"></script>

</body>

</html>