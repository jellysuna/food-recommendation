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
    echo "Connection failed : ". $e->getMessage();
}

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
