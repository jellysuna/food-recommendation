<?php
include("loginserv.php");

// Start the session
session_start();

$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
    $conn = new PDO("mysql:host=$sName; dbname=$dbname", $uName, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acc_id = $_POST['acc_id'];
    $water_consumed = $_POST['water_consumed'];

    // Check if there is already a record for the current day
    $stmtCheck = $conn->prepare("SELECT * FROM `waterintake` WHERE acc_id = ? AND DATE(waterintake_date) = CURDATE()");
    $stmtCheck->execute([$acc_id]);

    if ($stmtCheck->rowCount() > 0) {
        // If a record exists, update the existing record
        $stmtUpdate = $conn->prepare("UPDATE `waterintake` SET water_consumed = water_consumed + ? WHERE acc_id = ? AND DATE(waterintake_date) = CURDATE()");
        $stmtUpdate->execute([$water_consumed, $acc_id]);

    } else {
        // If no record exists, insert a new record
        $stmtInsert = $conn->prepare("INSERT INTO `waterintake` (acc_id, water_consumed, waterintake_date) VALUES (?, ?, CURRENT_TIMESTAMP)");
        $stmtInsert->execute([$acc_id, $water_consumed]);
    }

    $response = ["success" => true, "message" => "Water intake saved successfully"];
    echo json_encode($response);

    // Update session variable for total water consumed 
    $_SESSION['total_water_consumed'] += $water_consumed;
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}

if (!isset($_SESSION['total_water_consumed'])) {
    $_SESSION['total_water_consumed'] = 0;
}

$waterintake = $conn->prepare("SELECT * FROM `waterintake` WHERE acc_id = ? ORDER BY waterintake_id");
$acc_id = $_SESSION['acc_id'];
$waterintake->execute([$acc_id]);
?>