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

$feedback = $conn->query("SELECT *, account.acc_name FROM feedback JOIN account ON feedback.acc_id = account.acc_id ORDER BY feedback_id");
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <title>User Feedback</title>

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

    .containertitle {
      display: flex;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 20px;
      padding-top: 5px;
      height: 50px;
    }

    .containertxt {
      display: flex;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 20px;
      padding-top: 5px;
    }

    .container2 {
      margin-top: 0px;
      display: flex;
      overflow-x: hidden;
    }

    .containerss {
      width: 80%;
      position: top;
      background: #433e58;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 150px;
      padding-top: 25px;
    }

    .input-field textarea {
      width: 100%;
      padding: 10px;
      border: none;
      outline: none;
      font-size: 16px;
      border-top: 2px solid transparent;
      transition: all 0.2s ease;
      align-self: flex-start;
      min-height: 50px;
      max-height: 200px;
      resize: vertical;
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

    .input-field textarea {
      width: 1100px;
      padding: 10px;
      border: none;
      outline: none;
      font-size: 16px;
      border-radius: 10px;
      border-top: 2px solid transparent;
      transition: all 0.2s ease;
      align-self: flex-start;
      min-height: 200px;
      resize: vertical;
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
      margin-top: 10px;
    }

    .content h2 {
      padding-top: 30px;
      font-size: 35px;
      max-width: 750px;
      padding-left: 150px;
      color: #433e58;
    }

    .content h1 {
      padding-top: 70px;
      font-size: 30px;
      max-width: 750px;
      padding-left: 150px;
      color: #433e58;
    }

    .container2 h3 {
      color: #433e58;
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
      padding-left: 1020px;
    }

    .button button {
      border: none;
      color: #fff;
      font-size: 15px;
      font-weight: 500;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #706A88;
      cursor: pointer;
      transition: all 0.3s ease;
      height: 35px;
      width: 90px;
      margin-bottom: 20px;
      margin-left: 1106px;
    }

    .button button:hover {
      background: transparent;
      border: 1px solid #fff;
      color: #fff;
    }

    .submit-fb {
      align-items: left;
      margin-bottom: 20px;
      justify-content: space-between;
    }

    .container2 .submit-fb:nth-child(odd) {
      width: 1220px;
      position: top;
      background: #fff;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 150px;
      padding-top: 15px;
      margin-top: 20px;
      padding: 10px 30px 15px;
    }

    /* Apply styles to every even-numbered (2nd, 4th, 6th, etc.) feedback entry */
    .container2 .submit-fb:nth-child(even) {
      width: 1220px;
      position: top;
      background: #fff;
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      margin: 0 150px;
      padding-top: 15px;
      margin-top: 20px;
      padding: 10px 30px 15px;
    }

    .edit-button {
      background-color: #999;
      color: white;
      font-size: 12px;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      margin-left: 10px;
      cursor: pointer;
    }

    .edit-button:hover {
      background: transparent;
      border: 1px solid #999;
      color: #999;
    }

    .edit-feedback-desc {
      display: none;
      width: 100%;
      margin-bottom: 10px;
    }

    .save-button,
    .cancel-button {
      display: none;
      background-color: #706A88;
      color: #fff;
      font-size: 12px;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
      margin-left: 10px;
      transition: background-color 0.3s ease;
    }

    .save-button:hover,
    .cancel-button:hover {
      background-color: transparent;
      color: #706A88;
      border: 1px solid #706A88;
    }

    .feedback-entry.editing .feedback-desc {
      display: none;
    }

    .feedback-entry.editing .edit-feedback-desc,
    .feedback-entry.editing .save-button,
    .feedback-entry.editing .cancel-button {
      display: block;
    }

    .delete-button {
      display: inline-block;
      background-color: #ff6565;
      color: #fff;
      font-size: 12px;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
      margin-left: 10px;
      transition: background-color 0.3s ease;
    }

    .delete-button:hover {
      background-color: transparent;
      color: #ff6565;
      border: 1px solid #ff6565;
    }
  </style>
</head>

<body>
  <div class="container">
    <nav>
      <div class="logo">
        <a href="admin-page.php">
          <img src="img/0.1.png" alt="Logo">
        </a>
      </div>
    </nav>
  </div>

  <div class="content">
    <h2>User Feedback</h2>
  </div>

  <div class="container2">
    <div class="forms">
      <br>
      <?php
      if ($feedback->rowCount() <= 0) {
        echo "<br><div class='no-items'>
                \t\tNo feedback submitted.
            </div><style> padding-left: 50px;</stle>";
      }
      $entryCount = 0; // Initialize a loop counter
      ?>
      <?php while ($submitfeedback = $feedback->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="submit-fb feedback-entry-<?php echo $entryCount; ?>">
          <div class="feedback-header">
            <h3>
              <?php echo $submitfeedback['feedback_name']; ?><br>
            </h3>
            <h4>
              <?php echo 'Feedback ID: ' . $submitfeedback['feedback_id']; ?> | Uploaded by
              <?php echo 'User ID: ' . $submitfeedback['acc_id'] . ', ' . $submitfeedback['acc_name']; ?><br>
            </h4>
            <div class="feedback-desc-container">
              <div class="space"></div>
              <p id="feedback-desc-<?php echo $entryCount; ?>" class="feedback-desc">
                <?php echo $submitfeedback['feedback_desc']; ?>
              </p><br>
            </div>
            <small>
              <?php echo $submitfeedback['feedback_date'] ?>
            </small>
          </div>
        </div>
        <?php $entryCount++; // Increment the loop counter for the next entry ?>
      <?php } ?>
    </div>
  </div>

</body>

</html>