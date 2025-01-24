<?php
include("loginserv.php");

include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $acc_id = $_SESSION['acc_id'];
    $name = $_POST["name"];
    $ingredients = $_POST["ingredients"];
    $prepTime = $_POST["prepTime"];
    $calories = $_POST["calories"];
    $instructions = $_POST["instructions"];
    $historyNote = $_POST["historyNote"];

    try {
        $stmt = $conn->prepare("INSERT INTO `history`(`acc_id`, `recipe_name`, `recipe_ingredients`,  `recipe_preptime`, `recipe_calories`, `recipe_instruction`, `history_note`) VALUES (:acc_id, :name, :ingredients, :prepTime, :calories, :instructions, :historyNote)");

        // Bind parameters
        $stmt->bindParam(':acc_id', $acc_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':prepTime', $prepTime);
        $stmt->bindParam(':calories', $calories);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':historyNote', $historyNote);
        // Execute the prepared statement
        $stmt->execute();

        // Close the database connection
        $conn = null;

        // Send a success response to the client
        $response = ["success" => true, "message" => "Recipe saved successfully"];
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = ["success" => false, "message" => "Error saving recipe: " . $e->getMessage()];
        echo json_encode($response);
    }
} else {
    $response = ["success" => false, "message" => "Invalid request method"];
    echo json_encode($response);
}
?> 