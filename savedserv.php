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
    $note = $_POST["note"];

    try {
        $stmt = $conn->prepare("INSERT INTO `savedrecipe`(`acc_id`, `recipe_name`, `recipe_ingredients`,  `recipe_preptime`, `recipe_calories`, `recipe_instruction`, `savedrecipe_desc`) VALUES (:acc_id, :name, :ingredients, :prepTime, :calories, :instructions, :note)");

        // Bind parameters
        $stmt->bindParam(':acc_id', $acc_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':ingredients', $ingredients);
        $stmt->bindParam(':prepTime', $prepTime);
        $stmt->bindParam(':calories', $calories);
        $stmt->bindParam(':instructions', $instructions);
        $stmt->bindParam(':note', $note);
        // Execute the prepared statement
        $stmt->execute();

        $conn = null;

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