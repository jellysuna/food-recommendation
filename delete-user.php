<?php
include 'deleteserv.php'
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Delete</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
  <div class="container">
    <div class="forms"> 
      <div class="form login">
        <span class="title">Delete Account</span>
        <form action="" method="post" >
          <div class="input-field">
            <input type="text" required placeholder="Enter your email" id="acc_email" name="acc_email"><br/><br/>
            <i class="uil uil-envelope icon"></i>
          </div>
          <div class="input-field">
            <input type="password" required placeholder="Enter your password" id="acc_password" name="acc_password"><br/><br/>
            <i class="uil uil-lock icon"></i>
            <i class="uil uil-eye-slash showHidePw" id="showHideIcon" onclick="myFunction()"></i>
            <div  class="space2"></div>
          </div>

          <div class="input-field button">
            <input type="submit" name="submit" value="Delete my account">
          </div>
          <div class="input-field button">
            <input type="button" class="logout-button" value="Cancel" onclick="location.href='user-profile.php'">
          </div>
          <span><?php echo $success; ?></span>
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