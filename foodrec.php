<?php
include("loginserv.php");
include("config.php");

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <link rel="stylesheet" href="foodrec.css">

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