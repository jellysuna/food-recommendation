<?php
include("loginserv.php");
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selectedRating'])) {
        $selectedRating = $_POST['selectedRating'];

        $acc_id = $_SESSION['acc_id']; 

        echo '<script>';
        echo 'console.log("Selected Rating: ' . $selectedRating . '")';
        echo 'console.log("User ID: ' . $acc_id . '")';
        echo '</script>';

        $stmt = $conn->prepare("INSERT INTO ratings (acc_id, rec_ratings) VALUES (:acc_id, :rec_ratings)");

        $stmt->bindParam(':acc_id', $acc_id);
        $stmt->bindParam(':rec_ratings', $selectedRating);

        try {
            $stmt->execute();
            echo "Rating saved successfully<br>";
        } catch (PDOException $e) {
            echo "Error saving rating: " . $e->getMessage();
        }
    } else {
        echo "No rating data received<br>";
    }
}
?>