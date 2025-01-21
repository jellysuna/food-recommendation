<?php
include 'loginserv.php'
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="login1.css">
</head>

<body>
  <div class="containers">
    <p class="logo-text">Login to dishcover</p>
    <div  class="space"></div>
    <a href="chooseuser.php"> 
    <img src="img/chef2.png" alt="Logo" class="logo"></a>
  </div>
  <div  class="space"></div>

  <div class="container">
    <div class="forms"> 
      <div class="form login">
        <span class="title">Log in</span>
        <form action="" method="post" >
          <div class="input-field">
            <input type="text" required placeholder="Enter your username" id="acc_name" name="acc_name"><br/><br/>
            <i class="uil uil-user icon"></i>
          </div>
          <div class="input-field">
            <input type="password" required placeholder="Enter your password" id="acc_password" name="acc_password"><br/><br/>
            <i class="uil uil-lock icon"></i>
            <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
          </div>
          <div class="checkbox-text">
            <span></span>
            <a href="#" class="text">Forgot password?</a>
          </div>
          <div class="input-field button">
            <input type="submit" value="Log in" name="submit">
          </div>
          <div class="login-signup">
            <span class="text">Don't have an account?
                <a href="register.php" class="text signup-link">Sign up </a>
            </span>
          </div>
          <span><?php echo $invalid; ?></span>
        </form>
      </div>
    </div> 
  </div>

  <script src="jstry.js"></script> 
  <script>
    function myFunction() {
      var x = document.getElementById("acc_password");
      var icon = document.getElementById("showHideIcon");
      
      if (x.type === "password") {
        x.type = "text";
        icon.classList.add("uil-eye");
        icon.classList.remove("uil-eye-slash");
      } else {
        x.type = "password";
        icon.classList.remove("uil-eye");
        icon.classList.add("uil-eye-slash");
      }
    }
  </script>

</body>
</html>