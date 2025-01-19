<?php
include 'groceryserv.php';

if (isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // Perform the database query to remove the item with the given ID
    $stmt = $conn->prepare("DELETE FROM grocerylist WHERE grocery_id = ?");
    $result = $stmt->execute([$itemId]);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>