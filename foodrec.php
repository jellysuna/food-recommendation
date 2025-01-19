<?php
include("loginserv.php");
$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
  $conn = new PDO("mysql:host=$sName; dbname=$dbname", $uName, $pass);

  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed : " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <!-- ===== CSS ===== -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppin', sans-serif;
    }


    body {
      background-color: #b7adde;
      height: 100vh;
      font-family: 'Poppins', sans-serif;

    }


    /* Styles for small screens */
    @media screen and (max-width: 600px) {
      .container {
        margin-top: auto;
        padding: 10px;
      }
    }

    /* Styles for medium screens */
    @media screen and (min-width: 601px) and (max-width: 900px) {
      .container {
        margin-top: auto;
        padding: 30px;
      }
    }

    /* Styles for large screens */
    @media screen and (min-width: 901px) {
      .container {
        margin-top: auto;
        padding: 50px;
      }
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
      padding-left: 20px;
    }

    .logo img {
      max-height: 28px;
    }

    .container {
      margin-top: 0px;
      overflow-y: auto;
      overflow-x: auto;
      display: flex;
    }

    .containertxt {
      display: flex;
      width: 80%;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 150px;
      padding-top: 25px;
    }

    .submit-fb small {
      color: #999;
      font-size: 12px;
      padding-top: 5px;
      padding-left: 980px;
    }

    .container2 {
      margin-top: 0px;
      display: flex;
      overflow-x: hidden;
    }

    .containertxt.active .forms {
      height: 600px;
    }

    .input-field {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .input-field input {
      width: 1100px;
      padding-left: 20px;
      border: none;
      outline: none;
      font-size: 16px;
      border-top: 2px solid transparent;
      transition: all 0.2s ease;
    }

    .input-field input:is(:focus, :valid) {
      border-bottom-color: #b7adde;
    }

    .input-field i {
      order: -1;
      padding-left: 30px;
      color: #999;
      font-size: 30px;
      transition: all 0.2s ease;
    }


    .input-field input:is(:focus, :valid)~i {
      color: #4070f4;
    }

    .input-field i.icon {
      left: 0;
      position: top;
    }

    .image {
      display: flex;
      justify-content: flex-end;
      margin-right: 30px;
      margin-top: 90px;
    }

    .image img {
      max-width: 100%;
      margin-right: 120px;
    }

    .containerss {
      display: grid;
      padding-left: 40px;
      align-items: center;
      grid-template-columns: 2fr 1.5fr 1fr;
      column-gap: 20px;
    }

    .space {
      margin-top: 20px;
    }

    .content h2 {
      font-size: 40px;
      max-width: 750px;
      padding-left: 150px;
      color: #433e58;

    }

    .button input {
      border: none;
      color: #fff;
      font-size: 15px;
      font-weight: 500;
      size: 100px;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #706A88;
      cursor: pointer;
      transition: all 0.3s ease;
      height: 35px;
      width: 250px;
      margin-left: 1128px;
    }

    .button input:hover {
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .gui input {
      display: flex;
      justify-content: center;
      align-items: center;
      border: none;
      color: #fff;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #b7adde;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 5px 14px;
      height: fit-content;
      width: fit-content;
      margin-right: 10px;
    }

    .gui i {
      font-size: 20px;
      margin-bottom: 0;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .gui i:hover {
      background: transparent;
      color: #433e58;
    }

    .button-container {
      display: flex;
      padding-left: 1000px;
    }

    .container2 p {
      color: #999;
      font-size: 15px;
    }

    .space2 {
      margin-bottom: 60px;
    }

    .space3 {
      padding-bottom: 60px;
    }

    #recommendationsContainer {
      padding-left: 40px;
      padding-right: 40px;
      padding-top: 5px;
      padding-bottom: 20px;
      align-items: center;
      background-color: #fff;
      border-radius: 10px;
      margin: 0 150px;
    }

    #recommendationsContainer div {
      margin-top: 10px;
      margin-bottom: 10px;
    }

    #recommendationsContainer strong {
      color: #433e58;
    }

    #recommendationsContainer div:last-child hr {
      display: none;
    }

    #recommendationsContainer hr {
      border: none;
      border-top: 1px solid #b7adde;
    }

    .feedback-desc {
      padding-bottom: 10px;
    }

    .rate {
      float: left;
      height: 46px;
      padding: 0 10px;
    }

    .rate:not(:checked)>input {
      position: absolute;
      top: -9999px;
    }

    .rate:not(:checked)>label {
      float: right;
      width: 1em;
      overflow: hidden;
      white-space: nowrap;
      cursor: pointer;
      font-size: 30px;
      color: #ccc;
    }

    .rate:not(:checked)>label:before {
      content: '★ ';
    }

    .rate>input:checked~label {
      color: #ffc700;
    }

    .rate:not(:checked)>label:hover,
    .rate:not(:checked)>label:hover~label {
      color: #deb217;
    }

    .rate>input:checked+label:hover,
    .rate>input:checked+label:hover~label,
    .rate>input:checked~label:hover,
    .rate>input:checked~label:hover~label,
    .rate>label:hover~input:checked~label {
      color: #c59b08;
    }

    #saveRecipePopup,
    #addToHistoryPopup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      line-height: 1.6;
      opacity: 1;
    }

    #saveRecipePopup p,
    h2 {
      color: #433e58;
    }

    #saveRecipePopup label {
      font-weight: bold;
      color: #433e58;
    }

    #addToHistoryPopup p,
    h2 {
      color: #433e58;
    }

    #addToHistoryPopup label {
      font-weight: bold;
      color: #433e58;
    }

    #saveRecipePopup textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #saveRecipePopup input[type="submit"],
    #saveRecipePopup input[type="button"] {

      justify-content: center;
      align-items: center;
      border: none;
      color: #fff;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #433e58;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 5px 14px;
      height: fit-content;
      width: fit-content;
      margin-left: 10px;
      margin-top: 10px;
    }

    #saveRecipePopup input[type="submit"]:hover,
    #saveRecipePopup input[type="button"]:hover {
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    #addToHistoryPopup textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #addToHistoryPopup input[type="submit"],
    #addToHistoryPopup input[type="button"] {

      justify-content: center;
      align-items: center;
      border: none;
      color: #fff;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #433e58;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 5px 14px;
      height: fit-content;
      width: fit-content;
      margin-left: 10px;
      margin-top: 10px;
    }

    #addToHistoryPopup input[type="submit"]:hover,
    #addToHistoryPopup input[type="button"]:hover {
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }
  </style>
  <title>Food Recommendation System</title>
</head>

<body>
  <div class="container">
    <nav>
      <div class="logo">
        <a href="login-access.php">
          <img src="img/0.1.png" alt="Logo">
        </a>
      </div>
    </nav>
  </div>

  <div class="content">
    <h2>Food Recommendation</h2>
  </div>
  <div class="space"></div>

  <div class="containertxt">
    <div class="forms">
      <form id="recommendationForm">
        <div class="input-field">
          <input type="text" required placeholder="Enter your ingredients (separate with comma)" id="recipe_ingredients"
            name="recipe_ingredients"><br /><br />
          <i class="uil uil-search icon"></i>
        </div>
      </form>
    </div>
  </div>
  <div class="space"></div>
  <div class="containertxt">
    <div class="forms">
      <form id="preparationTimeForm">
        <div class="input-field">
          <input type="text" required placeholder="Enter preparation time in minutes" id="recipe_preptime"
            name="recipe_preptime"><br /><br />
          <i class="uil uil-search icon"></i>
        </div>
      </form>
    </div>
  </div>
  <div class="space"></div>

  <div class="input-field button">
    <input type="submit" name="getrec" id="getRecommendationsButton" value="Get recommendations">
  </div>
  <div class="space2"></div>

  <div id="loadingSpinner" style="display: none; padding-left: 150px; padding-bottom: 20px;">
    Loading...
  </div>

  <div id="recommendationsContainer">
    <div class="submit-fb feedback-entry">
      <form action="" method="post" style="display: inline;">
        <div class="feedback-header">
          <br>
          <p id="feedback-desc-" class="feedback-desc">
            <strong>Your Recommendation will be generated here</strong>
        </div>
      </form>

    </div>
  </div>
  <div class="space3"> </div>
  <div id="ratingsContainerWrapper" style="display: none;">
    <div id="recommendationsContainer">
      <div class="submit-fb feedback-entry">
        <form action="" method="post" style="display: inline;" id="ratingsForm">
          <br>
          <p id="feedback-desc-" class="feedback-desc">
            <strong> How do you like the recommendations?</strong>
          </p>
          <p> Please rate from 1 (Poor) to 5 (Very Satisfactory)</p>
          <div class="rate" id="starRating">
            <input type="radio" id="star5" name="rate" value="5" />
            <label for="star5" title="text">5 stars</label>
            <input type="radio" id="star4" name="rate" value="4" />
            <label for="star4" title="text">4 stars</label>
            <input type="radio" id="star3" name="rate" value="3" />
            <label for="star3" title="text">3 stars</label>
            <input type="radio" id="star2" name="rate" value="2" />
            <label for="star2" title="text">2 stars</label>
            <input type="radio" id="star1" name="rate" value="1" />
            <label for="star1" title="text">1 star</label>
          </div>
          <input type="hidden" id="selectedRating" name="selectedRating" value="">
          <br><br>
          <input type="submit" id="submitRatingForm" style="display: none;">

          <div class="space2"></div>
        </form>
      </div>
    </div>
    <div class="space3"></div>
  </div>

  <div id="saveRecipePopup" class="popup">
    <h2>Save Recipe</h2>
    <div class="space"></div>
    <div id="recipeInfo">
      <p><strong>Recipe Name:</strong> <span id="recipeName"></span></p>
      <p><strong>Ingredients:</strong> <span id="ingredients"></span></p>
      <p><strong>Preparation Time:</strong> <span id="prepTime"></span></p>
      <p><strong>Calories:</strong> <span id="calories"></span></p>
      <p><strong>Instructions:</strong> <span id="instructions"></span></p>
    </div>
    <form id="saveRecipeForm">
      <label for="note">Add description:</label><br>
      <textarea id="note" name="note"></textarea><br>
      <input type="submit" value="Save">
      <input type="button" value="Cancel" href="#" onclick="clearTextarea(); hide('saveRecipePopup')">
    </form>
  </div>
  <div id="addToHistoryPopup" class="popup">
    <h2>Tried this? Add to History</h2>
    <div class="space"></div>
    <div id="historyRecipeInfo">
      <p><strong>Recipe Name:</strong> <span id="historyRecipeName"></span></p>
      <p><strong>Ingredients:</strong> <span id="historyIngredients"></span></p>
      <p><strong>Preparation Time:</strong> <span id="historyPrepTime"></span></p>
      <p><strong>Calories:</strong> <span id="historyCalories"></span></p>
      <p><strong>Instructions:</strong> <span id="historyInstructions"></span></p>
    </div>
    <form id="addToHistoryForm" action="" method="post">
      <label for="historyNote">Add description:</label><br>
      <textarea id="historyNote" name="historyNote"></textarea><br>
      <input type="submit" value="Add to History">
      <input type="button" value="Cancel" href="#" onclick="clearTextarea(); hide('addToHistoryPopup')">
    </form>
  </div>



  <script>
    function clearTextarea() {
      document.getElementById('note').value = '';
      document.getElementById('historyNote').value = '';
    }
    let currentRecipeInfo;

    // Function to show the save popup
    function showSavePopup(name, ingredients, prepTime, calories, instructions) {
      // Store the recipe information in the global variable
      currentRecipeInfo = {
        name: name,
        ingredients: ingredients,
        prepTime: prepTime,
        calories: calories,
        instructions: instructions
      };

      document.getElementById('saveRecipePopup').style.display = 'block';

      // Populate the recipeInfo with the recommendation data
      document.getElementById('recipeName').textContent = name;
      document.getElementById('ingredients').textContent = ingredients;
      document.getElementById('prepTime').textContent = prepTime;
      document.getElementById('calories').textContent = calories;
      document.getElementById('instructions').textContent = instructions;
    }

    function hide(elementId) {
      document.getElementById(elementId).style.display = 'none';
    }

    document.getElementById('saveRecipeForm').addEventListener('submit', function (event) {
      event.preventDefault();

      // Retrieve the recipe information from the global variable
      const recipeInfo = currentRecipeInfo;

      const note = document.getElementById('note').value;
      console.log('Note:', note);

      // Create a FormData object
      const formData = new FormData();

      // Add hidden fields with recipe information
      formData.append('name', recipeInfo.name);
      formData.append('ingredients', recipeInfo.ingredients);
      formData.append('prepTime', recipeInfo.prepTime);
      formData.append('calories', recipeInfo.calories);
      formData.append('instructions', recipeInfo.instructions);
      formData.append('note', note);

      fetch('savedserv.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      }).then(response => response.json())
        .then(data => {
          console.log('Server Response:', data);
          if (data.success) {
            alert('Recipe saved successfully!');
            hide('saveRecipePopup');
          } else {
            alert('Error saving recipe. ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error during fetch:', error);
          alert('An error occurred while saving the recipe.');
        });
      clearTextarea();
    });


    function showAddToHistoryPopup(name, ingredients, prepTime, calories, instructions) {
      currentRecipeInfo = {
        name: name,
        ingredients: ingredients,
        prepTime: prepTime,
        calories: calories,
        instructions: instructions
      };
      document.getElementById('addToHistoryPopup').style.display = 'block';
      // Populate the recipeInfo with the recommendation data
      document.getElementById('historyRecipeName').textContent = name;
      document.getElementById('historyIngredients').textContent = ingredients;
      document.getElementById('historyPrepTime').textContent = prepTime;
      document.getElementById('historyCalories').textContent = calories;
      document.getElementById('historyInstructions').textContent = instructions;
    }

    document.getElementById('addToHistoryForm').addEventListener('submit', function (event) {
      event.preventDefault();
      const recipeInfo = currentRecipeInfo;

      const historyNote = document.getElementById('historyNote').value;

      const formData = new FormData();

      // Add hidden fields with recipe information
      formData.append('name', recipeInfo.name);
      formData.append('ingredients', recipeInfo.ingredients);
      formData.append('prepTime', recipeInfo.prepTime);
      formData.append('calories', recipeInfo.calories);
      formData.append('instructions', recipeInfo.instructions);
      formData.append('historyNote', historyNote);

      fetch('historyserv.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      }).then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Recipe added to history successfully!');
            hide('addToHistoryPopup');
          } else {
            alert('Error adding recipe to history. ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error during fetch:', error);
          alert('An error occurred while adding the recipe to history.');
        });
      clearTextarea();
    });


    document.getElementById('getRecommendationsButton').addEventListener('click', function (event) {
      event.preventDefault(); 

      document.getElementById('loadingSpinner').style.display = 'block';

      // Get user input from the form fields
      const ingredientsInput = document.getElementById('recipe_ingredients').value;
      const prepTimeInput = document.getElementById('recipe_preptime').value;

      fetch('http://localhost:5000/get_recommendations', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',  
        },
        body: JSON.stringify({
          recipe_ingredients: ingredientsInput,
          recipe_preptime: prepTimeInput,
        }),
        credentials: 'same-origin',  
      })
        .then(response => response.json())
        .then(data => {
          document.getElementById('loadingSpinner').style.display = 'none';

          console.log('Received data from Flask server:', data);

          const recommendationsContainer = document.getElementById('recommendationsContainer');
          recommendationsContainer.innerHTML = ''; 

          // Loop through the recommendations and append them to the container
          data.forEach(recommendation => {
            const recommendationElement = document.createElement('div');
            recommendationElement.innerHTML = `
                    <div class="submit-fb feedback-entry">
                        <form action="" method="post" style="display: inline;">
                            <div class="feedback-header">
                                <div class="button-container">
                                    <div class="input-field gui">
                                    <i class="uil uil-plus icon" onclick="showAddToHistoryPopup('${recommendation.title}', '${recommendation.ingredients}', '${recommendation.prep_time}', '${recommendation.calories}', '${recommendation.instructions}')"></i>
                                    </div>
                                    <div class="input-field gui">
                                    <i class="uil uil-heart icon" onclick="showSavePopup('${recommendation.title}', '${recommendation.ingredients}', '${recommendation.prep_time}', '${recommendation.calories}', '${recommendation.instructions}')"></i>
                                    </div>
                                </div>
                                <p class="feedback-desc">
                                    <strong>Recipe Name:</strong> ${recommendation.title}
                                </p>
                                <p class="feedback-desc">
                                    <strong>Ingredients:</strong> ${recommendation.ingredients}
                                </p>
                                <p class="feedback-desc">
                                    <strong>Preparation Time:</strong> ${recommendation.prep_time}
                                </p>
                                <p class="feedback-desc">
                                    <strong>Calorie:</strong> ${recommendation.calories}
                                </p>
                                <p class="feedback-desc">
                                    <strong>Instruction:</strong> ${recommendation.instructions}
                                </p>
                                <p class="feedback-desc">
                                    <strong>Category:</strong> ${recommendation.category}
                                </p>
                            </div>
                        </form>
                    </div>
                    <hr> `;
            recommendationsContainer.appendChild(recommendationElement);

          });

          document.getElementById('ratingsContainerWrapper').style.display = 'block';
        })
        .catch(error => {
          document.getElementById('loadingSpinner').style.display = 'none';
          console.error('Error during fetch:', error);
        });
    });

    document.querySelectorAll('.rate input').forEach(function (radio) {
      radio.addEventListener('change', function () {
        const selectedRating = this.value;
        document.getElementById('selectedRating').value = selectedRating;

        document.getElementById('submitRatingForm').click();
      });
    });


    document.addEventListener('DOMContentLoaded', function () {
      const ratingForm = document.getElementById('ratingsForm');

      ratingForm.addEventListener('submit', function (event) {
        event.preventDefault(); 

        const selectedRating = document.querySelector('.rate input:checked').value;
        document.getElementById('selectedRating').value = selectedRating;

        // console.log('Selected Rating:', selectedRating);

        fetch('save-rating.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded', 
          },
          body: new URLSearchParams(new FormData(ratingForm)),
        })
          .then(response => response.json())
          .then(data => {
            console.log('Server Response:', data);
          })
          .catch(error => {
            console.error('Error:', error);
          });
      });
    });

  </script>

</body>

</html>