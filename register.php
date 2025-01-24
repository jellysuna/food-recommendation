<?php
   include 'signupserv.php'
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <!-- ===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <title>Register </title>
  <link rel="stylesheet" type="text/css" href="login1.css">
</head>

<body>
  <div class="containers">
    <p class="logo-text">Welcome to dishcover!</p>
    <div  class="space"></div>
    <a href="chooseuser.php"> 
    <img src="img/chef2.png" alt="Logo" class="logo"></a>
  </div>
  <div  class="space"></div>

  <div class="container">
    <div class="forms"> 
      <div class="form login">
        <span class="title">Join dishcover now</span>
        <form action="" method="post" >
          <div class="input-field">
            <input type="text" required placeholder="Enter your username" id="acc_name" name="acc_name"><br/><br/>
            <i class="uil uil-user icon"></i>
          </div>
          <div class="input-field">
            <input type="text" name="acc_email" required placeholder="Enter your email" required><br/><br/>
            <i class="uil uil-envelope icon"></i>
          </div>
          <div class="input-field">
            <input type="password" required placeholder="Create a password" id="acc_password" name="acc_password"><br/><br/>
            <i class="uil uil-lock icon"></i>
            <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
          </div>
          <div class="checkbox-text">
            <div class="checkbox-content">
              <input type="checkbox" id="termCon">
              <label for="termCon" class="text">I accepted all terms and conditions</label>
            </div>
          </div>
          <div class="input-field button">
            <input type="submit" name="submit" value="Sign up">
          </div>
          <div class="login-signup">
            <span class="text">Already have an account?
              <a href="login.php" class="text login-link">Log in</a>
            </span>
          </div>
          <span><?php echo $invalid; ?></span>
        </form>
      </div>
    </div>
  </div>

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