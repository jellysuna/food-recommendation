<?php
session_start(); 

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: chooseuser.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION['admin_id']);
    header("Location: chooseuser.php");
}

require 'config.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <!-- ===== Iconscout CSS ===== -->
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <title>Admin Page</title>
  <style>    
    body{
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #b7adde;
    }

    @import url('https://fonts.googleapis.com/css2?family=Karla:wght@300;400;500;600;700&display=swap');
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppin', sans-serif;
    }

    .container{
      margin-top: 380px; 
      overflow-y: auto;
      overflow-x: auto;
      display: flex;
      flex-direction: column;
      width: 100%;
      min-height: 100vh;
      background-position: center;
      background-size: cover;
      padding-top: 35px;
      padding-left: 8%;
      padding-right: 8%;
    }

    .containerss {
      display: grid;
      align-items: center; 
      padding-left: 20px;
      grid-template-columns: 2.5fr 0.5fr 1.3fr;
      column-gap: 30px;
    }

    .containerss3 {
      display: grid;
      align-items: center; 
      grid-template-columns: 0.2fr 0.2fr 0.2fr 0.23fr 1fr;
    }

    .containerss2 {
      display: grid;
      align-items: center; 
      grid-template-columns: 2fr 2fr ;
      column-gap: 10px;
    }

    img {
      max-width: 100%;
      max-height:100%;
    }

    .texts {
      font-size: 70px;
    }

    .containers {
      display: flex;
      overflow-x: hidden;
      padding-top: 50px;
      align-items: right;
      justify-content: right;
      flex-direction: column;
      overflow-y: auto;
    }

    .image {
      display: flex;
      justify-content: flex-end;
      margin-right: 30px;
      margin-top: 90px; /
    }
      
    .image img {
      max-width: 100%;
      margin-right: 120px;
    }

    .space {
      margin-top: 50px; 
      margin-right: 60px;
    }

    .space2 {
      margin-top: 60px; 
      margin-right: 60px;
    }

    .space3 {
      margin-top: 20px; 
      margin-right: 60px;
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
      padding: 10px 0;
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

    .journal img {
      max-height: 50px; 
      padding-left: 225px;
    }
    .grocery img {
      max-height: 50px; 
    }

    .favourite img {
      max-height: 55px; 
      margin-left: 20px;
    }

    span{
      color: #433e58;
      font-family: 'Poppins', sans-serif;
    }

    nav ul li{
      display: inline-block;
      list-style: none;
      margin: 10px 15px;
    }

    nav ul li a{
      text-decoration: none;
      color:#433e58; 
      transition: 0.5s;
      padding-right: 20px;
    }

    nav ul li a:hover{
      color: #f9f9fb;
    }

    .login{
      text-decoration: none;
      margin-right: 15px;
      font-size: 18px;
    }

    .btn{
      border-radius: 6px;
      border: 1px solid #fff;
      padding: 9px 18px;
      text: #fff;
      transition: 0.3s;
      font-size: 18px;
      display: flex;
      margin-right: 20px;
    }

    .buttons .btn:hover{
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
      cursor: pointer;
    }

    .buttons .btn2:hover{
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .buttons .btn2{
      color: #ffffff;
      text-decoration: none;
      background: #433e58;
      padding: 10px 65px;
      border-radius: 10px;
      transition: 0.3s;
      padding-right: 20px;
    }

    .buttons i{
      position: absolute;
      display: flex;
      top: 100%;
      margin-top: 275px;
      margin-left: 1335px;
      transform: translateY(-50%);
      color: #ffffff;
      font-size: 23px;
      transition: all 0.2s ease;
    }

    .buttons i.icon{
      left: 0;
      color: #fff;
    }

    .content{
      margin-top: 25%;
      max-width: 1000px;
      color: #433e58;
    }

    .content h2{
      font-size: 55px;
      max-width: 750px;
      padding-left: 20px;
    }

    .text h3{
      font-size: 55px;
      max-width: 750px;
      padding-left: 30px;
      background-color: #fff;
    }

    .content p{
      margin-top: 10px;
      line-height: 25px;
      max-width: 650px;
      padding-left: 20px;
    }

    a{
      color: #fff;
    }

    .link {
      margin-top: 30px;
      padding-left: 20px;
    }

    .link p{
      margin-top: 10px;
      line-height: 25px;
      max-width: 550px;
      color: #fff;
      visibility: visible;
    }

    .hire{
      color: #000;
      text-decoration: none;
      background: #fff;
      padding: 10px 25px;
      font-weight: bold;
      border-radius: 6px;
      transition: 0.3s;
    }

    .hire2{
      color: #433e58;
      position: relative;
      text-decoration: none;
      margin-top: 20px;
      background: #fff;
      padding: 25px 1120px;
      font-weight: bold;
      font-size: 40px;
      border-radius: 10px;
      transition: 0.3s;
      max-width: 100%;
      padding-left: 20px;
    }

    .hire3{
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 40px;
      text-align: start;
    }

    .hire4{
      color: #433e58;
      text-decoration: none;
      font-weight: bold;
      font-size: 30px;
      text-align: start;
    }

    .hire5{
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      font-size: 30px;
      text-align: start;
    }

    .hire6{
      color: #433e58;
      text-decoration: none;
      font-weight: bold;
      font-size: 20px;
      text-align: center;
      padding-left: 10px;
    }

    .container3 {
      background-color: #433e58;
      display: flex;
      position: relative;
      overflow-x: hidden;
      border-radius: 10px;
      transition: 0.3s;
      margin-left: 20px;
      align-items: left;
      justify-content: left;
      flex-direction: column;
      padding: 25px 5px;
      max-width: 540px;
      height: 250px;
    }

    .container4 {
      background-color: #fff;
      display: flex;
      position: relative;
      overflow-x: hidden;
      border-radius: 10px;
      transition: 0.3s;
      margin-left: 20px;
      align-items: left;
      justify-content: left;
      flex-direction: column;
      padding: 0px 25px;
      max-width: 670px;
      height: 130px;
    }

    .container5 {
      background-color: #433e58;
      display: flex;
      position: relative;
      overflow-x: hidden;
      border-radius: 10px;
      transition: 0.3s;
      margin-left: 20px;
      align-items: left;
      justify-content: left;
      flex-direction: column;
      padding: 0px 25px;
      max-width: 670px;
      height: 130px;
    }

    .container6 {
      background-color: #fff;
      display: flex;
      position: relative;
      overflow-x: hidden;
      border-radius: 10px;
      transition: 0.3s;
      margin-left: 20px;
      align-items: left;
      justify-content: left;
      flex-direction: column;
      padding: 0px 10px;
      width: 165px;
      height: 130px;
    }

    .content2{
      margin-top: 25%;
      color: #433e58;
    }

    .content3{
      color: #433e58;
    }

    .btn2{
      background: #ffffff;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
      font-size: 18px;
      margin-left: 1265px;
      justify-content: flex-end;
    }

    .link .hire:hover{
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .link .hire2:hover{
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    .container3:hover{
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .container4:hover{
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    .container5:hover{
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .container6:hover{
      background: transparent;
      border: 1px solid #433e58;
      color: #433e58;
    }

    /* Styles for small screens */
    @media screen and (max-width: 600px) {
      .content2 {
        margin-top: auto;
        padding: 10px;
      }
    }

    /* Styles for medium screens */
    @media screen and (min-width: 601px) and (max-width: 900px) {
      .content2 {
        margin-top: auto;
        padding: 30px;
      }
    }

    /* Styles for large screens */
    @media screen and (min-width: 901px) {
      .containers {
        margin-top: auto;
        padding: 50px;
      }
    }

    .link i.icon{
      right: 0;
      position: absolute;
      margin-left: 1225px;
      color: #433e58;
      padding: 0px 100px;
      font-size: 50px;
    }
  </style>
</head>

<body>
  <div class="container">
    <nav>
      <div class="logo">
        <a href="#"> 
        <img src="img/0.1.png" alt="Logo"></a>
      </div>
      <ul>
        <li><a href="admin-profile.php">Profile</a></li>
        <li><a href="admin-update.php">Update Recipe</a></li>
        <li><a href="admin-fb.php">User Feedback</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <div class="buttons">
        <form method="post">
          <input type="submit" name="logout" value="Log Out" class="btn"></input>
        </form>
      </div>
    </nav>

    <div class="containerss">
      <div class="content" id="hello">
        <h2>Hello, Admin.</h2>
        <p>Explore and manage your recipes here.<br>Ready to add a new culinary masterpiece? </p>
        <div class="link">
          <a href="admin-update.php" class="hire" >Let's start!</a>
        </div>
      </div>
      <div  class="space"></div>

      <div class="image">
        <img src="img/chef3.png">
      </div>    
    </div>
  </div>
</body>
</html>