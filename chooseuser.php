<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- ===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

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

    @media screen and (min-width: 601px) and (max-width: 900px) {
      .welcome {
        flex-direction: column;
        align-items: center;
      }

      .logo-text {
        margin-top: 20px;
        text-align: center;
      }

      .icons-container {
        flex-direction: column;
        padding: 20px;
      }

      .icons {
        margin: 10px 0;
      }
    }

    /* Styles for medium screens */
    @media screen and (min-width: 601px) and (max-width: 900px) {
      .icons-container {
        margin-top: auto;
        padding: 30px;
      }
    }

    /* Styles for large screens */
    @media screen and (min-width: 901px) {
      .icons-container {
        margin-top: auto;
        padding: 50px;
      }
    }

    .welcome {
      display: flex;
      align-items: flex-start;
    }

    .logo a {
      font-size: 28px;
      text-decoration: none;
      font-family: cookie;
      font-weight: bold;
      color: #433e58;
      margin-top: 60px;
      margin-left: 60px;
    }

    .logo img {
      max-height: 28px;
      margin-top: 60px;
    }

    .logo-text {
      margin-top: 80px;
      margin-left: 300px;
      font-size: 32px;
      font-weight: bold;
      color: #332c38;
      font-family: cookie;
      padding-top: 60px;

    }

    .user {
      padding-top: 25px;
      padding-left: 45px;
      font-family: cookie;
      font-weight: bold;
      text-decoration: none;
      font-size: 18px;
      color: #332c38;
    }

    .icons-container {
      padding-top: 60px;
      padding-left: 500px;
      display: flex;
      text-decoration: none;

    }

    .icons:hover {
      background: transparent;
      color: #fff;
    }

    .icons img:hover {
      background: darkgrey;
      border: 1px solid #fff;
      color: #fff;
    }

    .icons img {
      border-radius: 10px;
      transition: background-color 0.3s ease;
    }

    .icons {
      border-radius: 10px;
      width: 200px;
      height: 250px;
      margin: 0 10px;
      align-items: center;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      transition: background-color 0.2s ease;
    }

    .space {
      margin-left: 70px;
    }

    .icons a {
      text-decoration: none;
    }
  </style>

  <title>Welcome to Dishcover!</title>
</head>

<body>
  <div class="welcome">
    <div class="logo">
      <a href="dashboard.php">
        <img src="img/0.1.png" alt="Logo">
      </a>
    </div>
    <p class="logo-text">Welcome to Dishcover! Select user type</p>
  </div>

  <div class="icons-container">
    <div class="icons">
      <a href="adminlogin.php">
        <img src="img/admin.png" alt="Admin Icon">
        <p class="user">Admin</p>
      </a>
    </div>
    <div class="space"></div>

    <div class="icons">
      <a href="login.php">
        <img src="img/normaluser.png" alt="User Icon">
        <p class="user">User</p>
      </a>
    </div>
  </div>
</body>

</html>