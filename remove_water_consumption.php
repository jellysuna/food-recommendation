<?php
include("loginserv.php");
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acc_id = $_POST['acc_id'];
    $water_consumed = $_POST['water_consumed'];

    // Check if there is already a record for the current day
    $stmtCheck = $conn->prepare("SELECT * FROM `waterintake` WHERE acc_id = ? AND DATE(waterintake_date) = CURDATE()");
    $stmtCheck->execute([$acc_id]);

    if ($stmtCheck->rowCount() > 0) {
        // If a record exists, update the existing record
        $stmtUpdate = $conn->prepare("UPDATE `waterintake` SET water_consumed = GREATEST(water_consumed - ?, 0) WHERE acc_id = ? AND DATE(waterintake_date) = CURDATE()");
        $stmtUpdate->execute([$water_consumed, $acc_id]);
    }

    echo 'Update successful';

    // Update session variable for total water consumed
    $_SESSION['total_water_consumed'] -= $water_consumed;
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}
?>