<?php
include("loginserv.php");

$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
    $conn = new PDO("mysql:host=$sName; dbname=$dbname", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection error']);
    exit();
}

if (isset($_POST['save-cal'])) {
    $calorieintake_id = $_POST['calorieintake_id'];
    $edited_meal_name = $_POST['edit-meal-name'];
    $edited_calories = $_POST['edit-calories'];

    try {
        $updateStmt = $conn->prepare("UPDATE `calorieintake` SET `meal_name` = ?, `calories_consumed` = ? WHERE `calorieintake_id` = ?");
        $updateRes = $updateStmt->execute([$edited_meal_name, $edited_calories, $calorieintake_id]);

        if ($updateRes) {
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating feedback.']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error']);
        exit();
    }
} else {
    $response = [
        'success' => true,
        'remainingCalories' => $remainingCalories,  // Calculate the remaining calories
        'userCalorie' => $userCalorie,  // Get the user's daily calorie limit
    ];
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}


?>
