<?php
include("loginserv.php");
$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
    $conn = new PDO("mysql:host=$sName; dbname=$dbname", $uName, $pass);

    $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : ". $e->getMessage();
}

if(isset($_POST['grocery_items'])) {
    $acc_id = $_SESSION['acc_id'];
    $grocery_items = $_POST['grocery_items'];

    $stmt = $conn->prepare("INSERT INTO grocerylist(grocery_items, acc_id) VALUES(?, ?)");
    $res = $stmt->execute([$grocery_items, $acc_id]);
    if ($res) {
        header("Location: grocery.php");
        exit(); 
    } else {
        echo 'error';
    }
} 

?>
