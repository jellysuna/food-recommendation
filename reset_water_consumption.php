<?php
include("loginserv.php");
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acc_id = $_POST['acc_id'];

    // Delete the water consumption record for the current day
    $stmtDelete = $conn->prepare("DELETE FROM `waterintake` WHERE acc_id = ? AND DATE(waterintake_date) = CURDATE()");
    $stmtDelete->execute([$acc_id]);

    echo 'Reset successful';

    // Update session variable for total water consumed
    $_SESSION['total_water_consumed'] = 0;
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}
?>