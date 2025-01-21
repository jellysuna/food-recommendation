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

// Initialize total_water_consumed if not already set
if (!isset($_SESSION['total_water_consumed'])) {
  $_SESSION['total_water_consumed'] = 0;
}

$acc_id = $_SESSION['acc_id'];
$waterintake = $conn->prepare("SELECT SUM(water_consumed) AS total_water_consumed FROM waterintake WHERE acc_id = ?");
$waterintake->execute([$acc_id]);
$total_water_consumed = $waterintake->fetch(PDO::FETCH_ASSOC)['total_water_consumed'];
// Initialize total_water_consumed to 0
$total_water_consumed = 0;

if (isset($_SESSION['acc_id'])) {
  $acc_id = $_SESSION['acc_id'];

  $waterintake = $conn->prepare("SELECT SUM(water_consumed) AS total_water_consumed FROM waterintake WHERE acc_id = ?");
  $waterintake->execute([$acc_id]);
  $total_water_consumed = $waterintake->fetch(PDO::FETCH_ASSOC)['total_water_consumed'];

  // If total_water_consumed is null or not set, set it to 0
  $total_water_consumed = $total_water_consumed ?? 0;
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
      height: 100%;
      font-family: 'Poppins', sans-serif;
      margin-bottom: 15px;
    }

    .forms .no-items {
      padding-top: 15px;
      padding-left: 150px;
      color: #565353;
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

    .glass-container {
      display: flex;
    }

    .containerss {
      width: 60%;
      margin: 0 auto;
      align-items: center;
      background: #fff;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin-top: 20px;
      padding-top: 25px;
      padding-bottom: 30px;
      margin-bottom: 40px;
    }

    .containertxt.active .forms {
      height: 600px;
    }

    .input-field {
      display: flex;
      resize: vertical;
      align-items: flex-start;
    }

    .input-field input {
      width: 1100px;
      padding-left: 20px;
      border: none;
      outline: none;
      font-size: 16px;
      border-top: 2px solid transparent;
      transition: all 0.2s ease;
      align-self: flex-start;
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
      right: 200px;
      position: absolute;
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

    .space {
      margin-top: 60px;
    }

    .space2 {
      margin-top: 100px;
    }

    .space3 {
      margin-left: 635px;
    }

    .space4 {
      margin-top: 20px;
    }

    .content h2 {
      font-size: 35px;
      max-width: 950px;
      margin-left: 370px;
      color: #433e58;
    }

    .details {
      text-align: center;
    }

    .details h1 {
      font-size: 28px;
      color: #433e58;
      padding-bottom: 20px;
    }

    .details h3 {
      color: #50B2E9;
      padding-bottom: 30px;
      font-size: 25px;
    }

    .container2 h4 {
      color: #433e58;
      padding-top: 3px;
      font-size: 12px;
    }

    .container2 small {
      color: #999;
      font-size: 12px;
      padding-top: 5px;
      padding-left: 920px;
    }

    .reset input {
      display: flex;
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
      margin-left: 740px;
      margin-top: 45px;
    }

    .reset input:hover {
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    .button input {
      display: flex;
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
      margin-top: 45px;
    }

    .button input:hover {
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    .glass {
      width: 70px;
      height: 130px;
      background-color: #b7e0f2;
      border-radius: 10px;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      color: #333;
      font-size: 14px;
      font-weight: bold;
      text-align: center;
      margin-left: 30px;
    }

    .water {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 0;
      background-color: #3498db;
      transition: height 0.5s ease;
    }

    .text {
      color: #fff;
      position: absolute;
      margin-bottom: 60px;
      font-weight: bold;
      font-size: 10px;
    }

    .white-text {
      color: white;
    }

    .details progress {
      height: 50px;
      width: 600px;
    }
  </style>

  <title>Food Recommendation System</title>
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
    <h2>Your water intake today is <span class="white-text">
      <?= $total_water_consumed ?> ml
      </span> out of <span class="white-text">2000 ml</span>
    </h2>
  </div>
  <div class="space"></div>

  <div class="containerss">
    <div class="details">
      <h1>Today</h1>
      <h3>
        <?= (2000 - $total_water_consumed) ?> ml remaining
      </h3>
      <progress id="waterProgress" value="<?= $total_water_consumed ?>" max="2000"></progress>
    </div>

    <div class="glass-container">
    </div>

    <div class="input-field reset">
      <input type="submit" id="resetButton" name="reset" value="Reset">
    </div>
  </div>
  <div class="space2"></div>

  <div class="containerss">
    <div class="details">
      <h1>Edit progress</h1>
    </div>
    <div class="space4"></div>

    <div class="glass-container">
      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>

      <div class="glass" onclick="fillGlass(this)">
        <div class="water"></div>
        <div class="text">250 ml</div>
      </div>
    </div>

    <div class="input-field button">
      <div class="space3"></div>

      <input type="submit" name="remove-water" value="Remove" onclick="removeWater()">
      <input type="submit" name="add-water" value="+ Add" onclick="addWater()">
    </div>
  </div>

  <script>
    document.getElementById('resetButton').addEventListener('click', function () {
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log('Reset Response:', xhr.responseText);

          resetGlasses();
        }
      };

      // Send data to the server
      const acc_id = <?php echo json_encode($_SESSION['acc_id']); ?>;
      xhr.open('POST', 'reset_water_consumption.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      const params = 'acc_id=' + acc_id;
      xhr.send(params);
    });
    let totalWaterConsumed = 0; 

    function fillGlass(glass) {
      const glasses = document.querySelectorAll('.glass');
      const clickedIndex = Array.from(glasses).indexOf(glass);

      glasses.forEach((g, index) => {
        const water = g.querySelector('.water');

        // Check if the glass is before or at the clicked index
        if (index <= clickedIndex) {
          water.style.height = '100%'; // Fill the glass
        } else {
          water.style.height = '0'; // Unfill the glass
        }
      });

      // Update the total water consumed
      totalWaterConsumed = (clickedIndex + 1) * 250;
    }

    function addWater() {
      // Calculate the amount of water based on the number of glasses clicked
      const glasses = document.querySelectorAll('.glass');
      const filledGlasses = Array.from(glasses).filter((g) => {
        const waterHeight = g.querySelector('.water').style.height;
        return waterHeight === '100%';
      });

      const glassesClicked = filledGlasses.length;
      const waterToAdd = glassesClicked * 250;

      console.log('Total water consumed:', totalWaterConsumed);
      console.log('Water to add:', waterToAdd);

      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        console.log('Ready state:', xhr.readyState);
        console.log('Status:', xhr.status);

        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log('Response:', xhr.responseText);

          // Update the progress bar value
          const progressBar = document.getElementById('waterProgress');
          const newTotal = totalWaterConsumed + waterToAdd;

        }
      };

      // Send data to the server
      const acc_id = <?php echo json_encode($_SESSION['acc_id']); ?>;
      xhr.open('POST', 'update_water_consumption.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      const params = 'acc_id=' + acc_id + '&water_consumed=' + waterToAdd;
      xhr.send(params);

      location.reload();
      resetGlasses();
    }

    function resetGlasses() {
      const glasses = document.querySelectorAll('.glass');
      glasses.forEach((g) => {
        const water = g.querySelector('.water');
        water.style.height = '0'; // Unfill all glasses
      });

      location.reload();
      // Reset the total water consumed
      totalWaterConsumed = 0;
    }

    function removeWater() {
      // Calculate the amount of water to remove based on the number of glasses clicked
      const glasses = document.querySelectorAll('.glass');
      const filledGlasses = Array.from(glasses).filter((g) => {
        const waterHeight = parseFloat(g.querySelector('.water').style.height);
        return waterHeight > 0; // Check if the glass is filled
      });

      const glassesClicked = filledGlasses.length;
      const waterToRemove = glassesClicked * 250;

      console.log('Total water consumed:', totalWaterConsumed);
      console.log('Water to remove:', waterToRemove);

      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        console.log('Ready state:', xhr.readyState);
        console.log('Status:', xhr.status);

        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log('Response:', xhr.responseText);

          // Update the progress bar value
          const progressBar = document.getElementById('waterProgress');
          const newTotal = totalWaterConsumed - waterToRemove;

        }
      };

      // Send data to the server
      const acc_id = <?php echo json_encode($_SESSION['acc_id']); ?>;
      xhr.open('POST', 'remove_water_consumption.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      const params = 'acc_id=' + acc_id + '&water_consumed=' + waterToRemove;
      xhr.send(params);

      location.reload();
      resetGlasses();
    }

  </script>


</body>
</html>