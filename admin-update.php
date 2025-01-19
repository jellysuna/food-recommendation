<?php
$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";


if (isset($_POST["submit"])) {
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "foodrecs");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve data from the form
    $recipe_name = mysqli_real_escape_string($conn, $_POST['recipe_name']);
    $recipe_preptime = mysqli_real_escape_string($conn, $_POST['recipe_preptime']);
    $recipe_ingredients = mysqli_real_escape_string($conn, $_POST['recipe_ingredients']);
    $recipe_instruction = mysqli_real_escape_string($conn, $_POST['recipe_instruction']);
    $recipe_calories = mysqli_real_escape_string($conn, $_POST['recipe_calories']);
    $recipe_category = mysqli_real_escape_string($conn, $_POST['recipe_category']);
    $recipe_ingredientsquantity = mysqli_real_escape_string($conn, $_POST['recipe_ingredientsquantity']);

    // SQL query to insert data into the recipe table
    $sql = "INSERT INTO `recipe` (`recipe_name`, `recipe_instruction`, `recipe_preptime`, `recipe_calories`, `recipe_ingredients`,  `recipe_category`, `recipe_ingredientsquantity`)
            VALUES ('$recipe_name', '$recipe_instruction', '$recipe_preptime', '$recipe_calories', '$recipe_ingredients', '$recipe_category', '$recipe_ingredientsquantity')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Recipe added successfully!');
        window.location.href = 'admin-update.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Add Recipe</title>
    <style>
        /* ===== Google Font Import - Poformsins ===== */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #b7adde;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        .form {
            width: 80%;
            height: 100%;
            background: none;
            overflow: hidden;
            padding-top: 85px;
            margin: 0 20px;
        }

        .form .title {
            position: relative;
            font-size: 27px;
            font-weight: 600;
        }

        .form .title::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 30px;
            background-color: #fff;
            border-radius: 25px;
        }

        .form .input-field {
            height: fit-content;
            width: 100%;
            margin-top: 30px;
        }

        .input-field {
            display: flex;
            flex-direction: column;
            margin-top: 30px;
        }

        .input-field p {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #332c38;
        }

        .input-field input,
        .input-field textarea {
            height: 60px;
            width: 100%;
            padding: 0 20px;
            border-radius: 10px;
            border: 2px solid transparent;
            outline: none;
            font-size: 16px;
            transition: all 0.2s ease;
        }

        .input-field input:focus,
        .input-field textarea:focus {
            border-color: #b7adde;
        }

        .input-field i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 23px;
            transition: all 0.2s ease;
        }

        .input-field input:focus~i,
        .input-field textarea:focus~i {
            color: #4070f4;
        }

        .input-field i.icon {
            left: 0;
        }

        .form .button {
            width: 10%;
            margin-top: 5px;
            margin-left: auto;
        }

        .form .button input {
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            border-radius: 6px;
            background-color: #332c38;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 16px;
            height: fit-content;
            width: fit-content;
        }

        .button input:hover {
            background: transparent;
            border: 1px solid #fff;
            color: #fff;
        }

        .form .login-signup {
            margin-top: 30px;
            text-align: center;
        }

        .containers {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }

        .logo {
            size: 10px;
            position: absolute;
            top: 0;
            left: 0;
        }

        nav {

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo a {
            font-size: 28px;
            text-decoration: none;
            font-family: cookie;
            font-weight: bold;
            color: #433e58;
            padding-left: 80px;
        }

        .logo img {
            max-height: 28px;
            margin-top: 40px;
        }

        .container {
            margin-top: 0px;
            overflow-y: auto;
            overflow-x: auto;
            display: flex;
        }

        @media (max-width: 800px) {
            .logo {
                display: none;
            }
        }

        .logo-text {
            margin-top: 10px;
            font-size: 22px;
            font-weight: bold;
            color: #332c38;
            font-family: cookie;
        }

        @media (max-width: 800px) {
            .logo-text {
                display: none;
            }
        }

        .space {
            margin-right: 25px;
        }

        .space2 {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <nav>
            <div class="logo">
                <a href="admin-page.php">
                    <img src="img/0.1.png" alt="Logo"></a>
            </div>
        </nav>
    </div>
    <div class="form">
        <div class="space2"></div>

        <span class="title">Add Recipe</span>
        <form action="" method="post">
            <div class="input-field">
                <p>Recipe Name</p>
                <input type="text" required placeholder="Recipe Name" id="recipe_name" name="recipe_name">
            </div>

            <div class="input-field">
                <p>Prep Time</p>
                <input type="text" required placeholder="Prep Time (in minutes)" id="recipe_preptime"
                    name="recipe_preptime">
            </div>

            <div class="input-field">
                <p>Ingredients</p>
                <textarea required placeholder="Ingredients (separate with comma)" id="recipe_ingredients"
                    name="recipe_ingredients"></textarea>
            </div>
            <div class="input-field">
                <p>Ingredients Quantity</p>
                <textarea required
                    placeholder="Ingredients quantity (separate with comma following ingredients sequence. eg: 1, 4, NA, 1/4)"
                    id="recipe_ingredientsquantity" name="recipe_ingredientsquantity"></textarea>
            </div>
            <div class="input-field">
                <p>Recipe Instruction</p>
                <textarea required placeholder="Recipe Instruction" id="recipe_instruction"
                    name="recipe_instruction"></textarea>
            </div>
            <div class="input-field">
                <p>Calories</p>
                <input type="text" required placeholder="Calories (Enter valid positive integer)" id="recipe_calories"
                    name="recipe_calories">
            </div>
            <div class="input-field">
                <p>Recipe Category</p>
                <input type="text" placeholder="Recipe Category" id="recipe_category" name="recipe_category">
            </div>

            <br>
            <div class="input-field button">
                <input type="submit" name="submit" value="Save">
            </div>

            <div class="input-field button">
                <input type="button" class="logout-button" value="Cancel" onclick="location.href='admin-page.php'">
            </div>
        </form>
    </div>
    <script src="jstry.js"></script>

</body>

</html>