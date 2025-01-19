<?php
$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$dbname", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>