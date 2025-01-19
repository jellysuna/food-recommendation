<?php 
include("loginserv.php");
$sName = "localhost";
$uName = "root";
$pass = "";
$dbname = "foodrecs";

try {
    $conn = new PDO("mysql:host=$sName; dbname=$dbname", $uName, $pass);

    $conn-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : ". $e->getMessage();
}


    $waterintake = $conn->prepare("SELECT * FROM `waterintake` WHERE acc_id = ? ORDER BY waterintake_id");
    $acc_id = $_SESSION['acc_id'];
    $waterintake->execute([$acc_id]);
    $filledGlasses = $waterintake->fetchAll(PDO::FETCH_COLUMN);

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
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppin', sans-serif;
}


body{
  background-color: #b7adde  ;
  height: 100%;
  font-family: 'Poppins', sans-serif;
  margin-bottom: 15px;
}

.forms .no-items{
  padding-top: 15px;
  padding-left: 150px;
  color:#565353;
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

nav{

display: flex;
align-items: center;
justify-content: space-between;
}

.logo a{
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

.glass-container{
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
}


.containertxt.active .forms{
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


.input-field input:is(:focus, :valid) ~ i{
  color: #4070f4;
}

.input-field i.icon{
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

.content h2{
  font-size: 35px;
  max-width: 950px;
  margin-left: 370px;
  color: #433e58;
}

.details {
    text-align: center; 
}

.details h1{
  font-size: 28px;
  color: #433e58;
  padding-bottom: 20px;
}

.details h3{
  color: #50B2E9;
  padding-bottom: 30px;
  font-size: 25px;
}

.container2 h4{
  color: #433e58;
  padding-top: 3px;
  font-size: 12px;
}

.container2 small{
  color:#999;
  font-size: 12px;
  padding-top: 5px;
  padding-left: 920px;
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
    margin-left: 740px; 
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
        .text{
            color: #fff;
            position: absolute;
            margin-bottom: 60px;
            font-weight: bold;
            font-size: 10px;
        }

.white-text {
    color: white;
}
</style>
    
         
    <title>Food Recommendation System</title> 
</head>

<body>
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
    <h2>Your water intake today is <span class="white-text">750 ml</span> out of <span class="white-text">1200ml</span></h2>
    </div>
    <div class="space"></div>

    <div class="containerss">
        <div class="details">
            <h1>Today</h1>
            <h3>450 ml remaining</h3>
        </div>

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
        <input type="button" value="Reset" onclick="resetMeals()">
    </div>  
    </div>

    <script>
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

        // Send the data to the server
        const acc_id = <?php echo json_encode($_SESSION['acc_id']); ?>;
        const glass_id = clickedIndex + 1; 

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_water_consumption.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText); 
            }
        };
        const params = 'acc_id=' + acc_id + '&glass_id=' + glass_id;
        xhr.send(params);
    }
    
</script>
</body>
</html>


