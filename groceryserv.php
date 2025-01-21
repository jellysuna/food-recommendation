<?php
include("loginserv.php");

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
