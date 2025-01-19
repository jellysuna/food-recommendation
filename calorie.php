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

if (isset($_POST['meal_name']) && isset($_POST['calories_consumed'])) {
    $acc_id = $_SESSION['acc_id'];
    $meal_name = $_POST['meal_name'];
    $calories_consumed = $_POST['calories_consumed'];

    $stmt = $conn->prepare("INSERT INTO calorieintake(meal_name, calories_consumed, acc_id) VALUES(?, ?, ?)");
    $res = $stmt->execute([$meal_name, $calories_consumed, $acc_id]);
    if ($res) {
        header("Location: calorie.php");
        exit();
    } else {
        echo 'error';
    }
}

if (isset($_POST['delete-cal'])) {
    $calorieintake_id = $_POST['calorieintake_id'];

    $stmt = $conn->prepare("DELETE FROM calorieintake WHERE calorieintake_id = ?");
    $result = $stmt->execute([$calorieintake_id]);

    if ($result) {
        header("Location: calorie.php");
        exit(); 
    } else {
        echo 'error';
    }
}

if (isset($_POST['save-cal'])) {
    $calorieintake_id = $_POST['calorieintake_id'];
    $edited_meal_name = $_POST['edit-meal-name'];
    $edited_calories = $_POST['edit-calories'];

    $updateStmt = $conn->prepare("UPDATE `calorieintake` SET `meal_name` = ?, `calories_consumed` = ? WHERE `calorieintake_id` = ?");
    $updateRes = $updateStmt->execute([$edited_meal_name, $edited_calories, $calorieintake_id]);

    if ($updateRes) {
        header("Location: calorie.php");
        exit();
    } else {
        echo 'Error updating feedback.';
    }
}
if (isset($_POST['reset-meals'])) {
    // Perform the database query to delete all meals for the current user
    $acc_id = $_SESSION['acc_id'];
    $resetStmt = $conn->prepare("DELETE FROM calorieintake WHERE acc_id = ?");
    $resetResult = $resetStmt->execute([$acc_id]);

    if ($resetResult) {
        header("Location: calorie.php");
        exit();
    } else {
        echo 'Error resetting meals.';
    }
}

$calorieintake = $conn->prepare("SELECT * FROM `calorieintake` WHERE acc_id = ? ORDER BY calorieintake_id");
$acc_id = $_SESSION['acc_id'];
$calorieintake->execute([$acc_id]);
$userCalorieQuery = $conn->prepare("SELECT user_calorie FROM account WHERE acc_id = ?");
$userCalorieQuery->execute([$acc_id]);
$userCalorieResult = $userCalorieQuery->fetch(PDO::FETCH_ASSOC);
$userCalorie = isset($userCalorieResult['user_calorie']) ? $userCalorieResult['user_calorie'] : 0;

$totalCaloriesConsumed = 0;

// Fetch all rows and calculate total calories consumed
$rows = $calorieintake->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $submitcal) {
    $totalCaloriesConsumed += $submitcal['calories_consumed'];
}

// Calculate the percentage of calories consumed relative to the total user calories
$percentageConsumed = 0; // Default value

// Check if $userCalorie is not zero to avoid division by zero
if ($userCalorie != 0) {
    $percentageConsumed = ($totalCaloriesConsumed / $userCalorie) * 100;
}

$remainingCalories = max(0, $userCalorie - $totalCaloriesConsumed);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Intake</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {

            font-family: 'Poppin', sans-serif;
        }


        body {
            background-color: #b7adde;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 15px;
        }

        /* Styles for small screens */
        @media screen and (max-width: 600px) {
            .container {
                margin-top: auto;
                padding: 10px;
            }
        }

        /* Styles for medium screens */
        @media screen and (min-width: 601px) and (max-width: 900px) {
            .container {
                margin-top: auto;
                padding: 30px;
            }
        }

        /* Styles for large screens */
        @media screen and (min-width: 901px) {
            .container {
                margin-top: auto;
                padding: 50px;
            }
        }

        nav {

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo a {
            font-size: 28px;
            text-decoration: none;
            font-family: cookie;
            font-weight: bold;
            color: #433e58;
            padding-left: 20px;
        }

        .logo img {
            max-height: 28px;
        }

        .container {
            margin-top: 0px;
            overflow-y: auto;
            overflow-x: auto;
            display: flex;
        }


        h1 {
            text-align: center;
            color: #333;
        }

        .space {
            margin-top: 10px;
        }

        .content h2 {
            font-size: 35px;
            max-width: 750px;
            padding-left: 150px;
            color: #433e58;
        }

        .progress-bar {
    margin-left: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 330px;
    height: 330px;
    border-radius: 50%;
    background: radial-gradient(closest-side, #b7adde 65%, transparent 10% 10%), conic-gradient(<?php echo "#92DDE6 {$percentageConsumed}%, #42ACAD 0"; ?>%);
}


        .details {
            background-color: #fff;
            margin-left: 200px;
            min-width: 700px;
            max-width: fit-content;
            max-height: fit-content;
            border-radius: 20px;
            padding-bottom: 15px;
        }

        .details h1 {
            text-align: start;
            margin-left: 35px;
            font-size: 25px;
            color: #433e58;
        }

        .details small {
            margin-left: 35px;
        }

        .details p {
            margin-left: 35px;
            font-size: 15px;
            margin-top: 2px;
            color: #433e58;
            font-weight: 550;
        }

        .progress-bar p {
            color: #fff;
            font-weight: 550;
            font-size: 16px;
        }

        .entry {
            display: flex;
            align-items: center;
        }

        .edit-button {
            background-color: #999;
            color: white;
            font-size: 11px;
            border: none;
            border-radius: 5px;
            padding: 3px 10px;
            margin-left: 380px;
            cursor: pointer;
        }

        .edit-button:hover {
            background: transparent;
            border: 1px solid #999;
            color: #999;
        }


        .delete-form {
            margin-left: 10px;
        }

        .delete-button {
            background-color: #ff6565;
            color: white;
            font-size: 11px;
            border: none;
            border-radius: 5px;
            padding: 3px 10px;
            cursor: pointer;
        }

        .delete-button:hover {
            background: transparent;
            border: 1px solid #ff6565;
            color: #ff6565;
        }

        .container2 {
            margin-top: 0px;
            display: flex;
            overflow-x: hidden;
        }

        .button-container {
            display: flex;
            margin-left: 20px;
        }

        .button input {
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            border-radius: 6px;
            background-color: #433e58;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px 14px;
            height: fit-content;
            width: fit-content;
            margin-left: 10px;
            margin-top: 10px;
        }

        .button input:hover {
            background: transparent;
            border: 1px solid #433e58;
            color: #433e58;
        }


        .edit-cal {
            display: none;
            width: 100%;
            margin-bottom: 10px;
        }

        .save-button,
        .cancel-button {
            display: none;
            background-color: #706A88;
            color: #fff;
            font-size: 12px;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }

        .save-button:hover,
        .cancel-button:hover {
            background-color: transparent;
            color: #706A88;
            border: 1px solid #706A88;
        }
    </style>

</head>

<body>
    <div class="container">
        <nav>
            <div class="logo">
                <a href="login-access.php">
                    <img src="img/0.1.png" alt="Logo"></a>
            </div>
        </nav>
    </div>

    <div class="content">
        <h2>Calorie Intake</h2>
    </div>

    <div class="container">
        <div class="progress-bar" role="progressbar">
            <p id="caloriesRemaining">
                <?php echo "$remainingCalories calories remaining"; ?>
            </p>
        </div>
        <div class="details">
            <h1>Today</h1>

            <?php
            if (empty($rows)) {
                echo "<br><div class='no-items' style='padding-left: 30px; padding-bottom: 10px;'>
                    No calories consumed today.
                </div>";
            }
            foreach ($rows as $submitcal) {
                $entryCount = $submitcal['calorieintake_id'];
                ?>
                <div class="entry">
                    <small>
                        <?php echo $submitcal['calorieintake_date'] ?>
                    </small>
                    <button class="edit-button" type="button" onclick="toggleEdit(<?php echo $entryCount; ?>)">Edit</button>

                    <form method="post" action="calorie.php" class="delete-form">
                        <input type="hidden" name="calorieintake_id" value="<?php echo $submitcal['calorieintake_id']; ?>">
                        <button class="delete-button" type="submit" name="delete-cal"
                            id="delete-button-<?php echo $entryCount; ?>">Delete</button>
                    </form>
                </div>

                <div class="container2">
                    <p id="meal-name-<?php echo $entryCount; ?>" class="meal-name">
                        <?php echo $submitcal['meal_name']; ?>
                    </p>
                    <p id="calorie-<?php echo $entryCount; ?>" class="calorie">
                        <?php echo $submitcal['calories_consumed']; ?> calories
                    </p>
                </div>

                <textarea id="edit-meal-name-<?php echo $entryCount; ?>" name="edit-meal-name" class="edit-cal" rows="2"
                    cols="30"><?php echo $submitcal['meal_name']; ?></textarea>
                <textarea id="edit-calories-<?php echo $entryCount; ?>" name="edit-calories" class="edit-cal" rows="2"
                    cols="30"><?php echo $submitcal['calories_consumed']; ?></textarea>
                <button class="cancel-button" type="button" id="cancel-button-<?php echo $entryCount; ?>"
                    onclick="cancelEdit(<?php echo $entryCount; ?>)">Cancel</button>
                <button class="save-button" name="save-cal" type="button" id="save-button-<?php echo $entryCount; ?>"
                    onclick="saveEdit(<?php echo $entryCount; ?>)">Save</button>

            <?php } ?>

            <div class="button-container">
                <div class="input-field button">
                    <input type="submit" name="submit" value="+ Add" onclick="addMeal()">
                </div>
                <div class="input-field button">
                    <input type="button" value="Reset" onclick="resetMeals()">
                </div>
            </div>

        </div>
    </div>

    <script>
        function resetMeals() {
            // Create a form with a hidden input to indicate the reset action
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'calorie.php'; 

            var resetInput = document.createElement('input');
            resetInput.type = 'hidden';
            resetInput.name = 'reset-meals'; 
            resetInput.value = '1';

            form.appendChild(resetInput);

            document.body.appendChild(form);
            form.submit();
        }

        function toggleEdit(entryCount) {
            // Hide the display elements for the specific entry
            document.getElementById(`meal-name-${entryCount}`).style.display = 'none';
            document.getElementById(`calorie-${entryCount}`).style.display = 'none';

            // Show the edit fields for the specific entry
            document.getElementById(`edit-meal-name-${entryCount}`).style.display = 'block';
            document.getElementById(`edit-calories-${entryCount}`).style.display = 'block';

            // Show the Save and Cancel buttons for the specific entry
            document.getElementById(`cancel-button-${entryCount}`).style.display = 'inline-block';
            document.getElementById(`save-button-${entryCount}`).style.display = 'inline-block';
        }

        function cancelEdit(entryCount) {
            // Show the display elements for the specific entry
            document.getElementById(`meal-name-${entryCount}`).style.display = 'block';
            document.getElementById(`calorie-${entryCount}`).style.display = 'block';

            // Hide the edit fields for the specific entry
            document.getElementById(`edit-meal-name-${entryCount}`).style.display = 'none';
            document.getElementById(`edit-calories-${entryCount}`).style.display = 'none';

            // Hide the Save and Cancel buttons for the specific entry
            document.getElementById(`cancel-button-${entryCount}`).style.display = 'none';
            document.getElementById(`save-button-${entryCount}`).style.display = 'none';
        }

        function saveEdit(entryCount) {
            const mealName = document.getElementById(`edit-meal-name-${entryCount}`).value;
            const caloriesConsumed = document.getElementById(`edit-calories-${entryCount}`).value;

            const formData = new FormData();
            formData.append('save-cal', '1');  
            formData.append('calorieintake_id', entryCount);  
            formData.append('edit-meal-name', mealName);
            formData.append('edit-calories', caloriesConsumed);

            fetch('save-cal.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the displayed values
                        document.getElementById(`meal-name-${entryCount}`).innerText = mealName;
                        document.getElementById(`calorie-${entryCount}`).innerText = `${caloriesConsumed} calories`;
                        // Hide the edit fields
                        cancelEdit(entryCount);
                        location.reload();
                    } else {
                        console.error('Server-side error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                });
        }

        function addMeal() {
        var mealName = prompt("Enter the meal name:");
        var calorieContent = prompt("Enter the calorie content:");

        if (mealName && calorieContent) {
            // Validate calorie content input (positive integer)
            if (!isNumeric(calorieContent) || calorieContent <= 0 || Math.floor(calorieContent) !== parseFloat(calorieContent)) {
                alert('Error: Invalid calorie content. Please enter a positive integer.');
                return;
            }

            // Create a new form element
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'calorie.php'; 

            // Create hidden input fields for meal_name and calories_consumed
            var mealNameInput = document.createElement('input');
            mealNameInput.type = 'hidden';
            mealNameInput.name = 'meal_name';
            mealNameInput.value = mealName;

            var calorieContentInput = document.createElement('input');
            calorieContentInput.type = 'hidden';
            calorieContentInput.name = 'calories_consumed';
            calorieContentInput.value = calorieContent;

            // Create a submit button
            var submitButton = document.createElement('input');
            submitButton.type = 'submit';
            submitButton.name = 'submit';
            submitButton.style.display = 'none';

            // Append input fields and submit button to the form
            form.appendChild(mealNameInput);
            form.appendChild(calorieContentInput);
            form.appendChild(submitButton);

            // Append the form to the document and submit it
            document.body.appendChild(form);
            submitButton.click();
            updateRemainingCalories();
        }
    }

    function isNumeric(value) {
        return !isNaN(parseFloat(value)) && isFinite(value);
    }

        function updateRemainingCalories() {
            // Fetch the updated remaining calories from the server
            fetch('getRemainingCalories.php') 
                .then(response => response.json())
                .then(data => {
                    document.getElementById('caloriesRemaining').innerText = `${data.remainingCalories} calories remaining`;
                })
                .catch(error => {
                    console.error('Error fetching remaining calories:', error);
                });
        }
    </script>

</body>

</html>