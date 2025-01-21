<?php
include 'groceryserv.php';

if (isset($_POST['remove-item'])) {
    $itemId = $_POST['grocery_id'];

    $stmt = $conn->prepare("DELETE FROM grocerylist WHERE grocery_id = ?");
    $result = $stmt->execute([$itemId]);

    if ($result) {
        header("Location: grocery.php");
        exit();
    } else {
        echo 'error';
    }
}

if (isset($_POST['grocery_bought'])) {
    $groceryId = $_POST['grocery_id'];
    $groceryBought = isset($_POST['grocery_bought']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE grocerylist SET grocery_bought = ? WHERE grocery_id = ?");
    $result = $stmt->execute([$groceryBought, $groceryId]);

    if ($result) {
        header("Location: grocery.php");
        exit();
    } else {
        echo 'error';
    }
}

if (isset($_POST['update-quantity'])) {
    $groceryId = $_POST['grocery_id'];
    $groceryQuantity = $_POST['grocery_quantity'];

    $stmt = $conn->prepare("UPDATE grocerylist SET grocery_quantity = ? WHERE grocery_id = ?");
    $result = $stmt->execute([$groceryQuantity, $groceryId]);

    if ($result) {
        header("Location: grocery.php");
        exit();
    } else {
        echo 'error';
    }
}

$grocerylist = $conn->prepare("SELECT * FROM grocerylist WHERE acc_id = ? ORDER BY grocery_id");
$acc_id = $_SESSION['acc_id'];
$grocerylist->execute([$acc_id]);
?>

<!DOCTYPE html>
<html lang="en">
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="grocery1.css">

    <title>Groceries</title>
</head>

<body>
    <div class="container">
        <nav>
            <div class="logo">
                <a href="login-access.php">
                    <img src="img/0.1.png" alt="Logo"></a>
            </div>
        </nav>
    </div>
    <div class="content">
        <h2>Groceries</h2>
    </div>
    <div class="space"></div>
    <div class="containertxt">
        <div class="forms">
            <form action="" method="post" autocomplete="off">
                <div class="input-field">
                    <input type="text" required placeholder="Add items" id="grocery_items"
                        name="grocery_items"><br /><br />
                    <i class="uil uil-plus icon"></i>
                    <div class="buttons">
                        <button type="submit" class="btn2" name="add-item">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="space2"></div>
    <div class="containertxt">
        <div class="forms">
            <?php
            if ($grocerylist->rowCount() <= 0) {
                echo "<div class='no-items'>
                    No grocery added.
                </div>";
            }
            ?>
            <?php while ($addgrocery = $grocerylist->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="grocery-item">
                    <form action="" method="post" class="grocery-form" style="display: flex; align-items: center;">
                        <input type="checkbox" name="grocery_bought" <?php echo $addgrocery['grocery_bought'] == 1 ? 'checked' : ''; ?> onchange="this.form.submit()">
                        <label for="termCon" class="<?php echo $addgrocery['grocery_bought'] == 1 ? 'bought' : 'input'; ?>">
                            <?php echo '&nbsp;' . $addgrocery['grocery_items']; ?>
                        </label>
                        <div class="quantity-update-container"
                            style="display: flex; align-items: center; margin-left: auto; margin-right: 10px;">
                            <input type="number" name="grocery_quantity"
                                value="<?php echo $addgrocery['grocery_quantity']; ?>" min="1"
                                style="width: 40px; margin-right: 10px; margin-left: 100px;">
                            <button type="submit" name="update-quantity"
                                style="background-color: #706A88; color: #ffffff; padding: 3px; border: none; border-radius: 5px; cursor: pointer;">Update</button>
                        </div>
                        <input type="hidden" name="grocery_id" value="<?php echo $addgrocery['grocery_id']; ?>">
                        <button type="submit" name="remove-item" class="remove-items" style="margin-left: 10px;">x</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>