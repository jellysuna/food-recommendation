<?php
include("loginserv.php");

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

require 'config.php';

if (isset($_POST['save-note'])) {
  $history_id = $_POST['history_id'];
  $edited_history_note = $_POST['edit-history-note'];

  $updateStmt = $conn->prepare("UPDATE history SET history_note = ? WHERE history_id = ?");
  $updateRes = $updateStmt->execute([$edited_history_note, $history_id]);

  if ($updateRes) {
    header("Location: history.php");
    exit();
  } else {
    echo 'Error updating feedback.';
  }
}

if (isset($_POST['delete-history'])) {
  $history_id = $_POST['history_id'];

  $stmt = $conn->prepare("DELETE FROM history WHERE history_id = ?");
  $result = $stmt->execute([$history_id]);

  if ($result) {
    header("Location: history.php");
    exit(); 
  } else {
    echo 'error';
  }
}

$history = $conn->prepare("SELECT * FROM history WHERE acc_id = ? ORDER BY history_id");
//$stmt = $conn->prepare("SELECT * FROM history WHERE acc_id = :acc_id");
//$stmt->bindParam(':acc_id', $acc_id);
$acc_id = $_SESSION['acc_id'];
$history->execute([$acc_id]);
?>

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

    .container2 p {
      color: #433e58;
      padding-top: 10px;
      font-size: 15px;
    }

    .container2 small {
      color: #999;
      font-size: 12px;
      padding-top: 5px;
      padding-left: 1030px;
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

    /* Apply styles to every odd-numbered (1st, 3rd, 5th, etc.) feedback entry */
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

    .button-container .delete-button {
      display: flex;
      justify-content: center;
      align-items: center;
      border: none;
      color: #fff;
      font-size: 12px;
      font-weight: 500;
      letter-spacing: 1px;
      border-radius: 6px;
      background-color: #ff6565;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 5px 14px;
      height: fit-content;
      width: fit-content;
      margin-right: 10px;
      margin-top: 10px;
    }

    .button-container .delete-button:hover {
      background-color: transparent;
      color: #ff6565;
      border: 1px solid #ff6565;
    }

    .label {
      font-weight: 550;
      color: #433e58;
    }

    .button-container .save-button,
    .button-container .cancel-button,
    .button-container .edit-button {
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
      margin-top: 10px;
    }

    .button-container .save-button:hover,
    .button-container .edit-button:hover,
    .button-container .cancel-button:hover {
      background: transparent;
      border: 1px solid #b7adde;
      color: #b7adde;
    }

    .button-container {
      padding-left: 1000px;
      display: flex;
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
    <h2>History</h2>
  </div>
  <div class="space"></div>

  <div class="container2">
    <div class="forms">
      <?php
      if ($history->rowCount() <= 0) {
        echo "<br><div class='no-items'>
                    \t\tNo history submitted.
                </div><style> padding-left: 50px;</style>";
      }
      $entryCount = 0; 
      ?>
      <?php while ($historyEntry = $history->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="submit-history history-entry-<?php echo $entryCount; ?>">
          <form action="" method="post" style="display: inline;">
            <div class="submit-fb feedback-entry">
              <div class="feedback-header">
                <small class="history-date">
                  <?php echo $historyEntry['history_date']; ?>
                </small>
                <p class="feedback-desc">
                  <strong>Recipe Name:</strong>
                  <?php echo $historyEntry['recipe_name']; ?>
                </p>
                <p class="feedback-desc">
                  <strong>Ingredients:</strong>
                  <?php echo $historyEntry['recipe_ingredients']; ?>
                </p>
                <p class="feedback-desc">
                  <strong>Preparation Time:</strong>
                  <?php echo $historyEntry['recipe_preptime']; ?>
                </p>
                <p class="feedback-desc">
                  <strong>Instruction:</strong>
                  <?php echo $historyEntry['recipe_instruction']; ?>
                </p>
                <p class="feedback-desc">
                  <strong>Calories:</strong>
                  <?php echo $historyEntry['recipe_calories']; ?>
                </p>
                <p class="feedback-desc-note">
                  <strong>Note:</strong>
                  <?php echo $historyEntry['history_note']; ?>
                  <textarea id="edit-history-note-<?php echo $entryCount; ?>" name="edit-history-note"
                    class="edit-history-note" style="display:none;" rows="3"
                    cols="70"><?php echo $historyEntry['history_note']; ?></textarea>
                </p>
              </div>

              <div class="button-container">
                <button class="edit-button" type="button"
                  onclick="editHistoryNote(<?php echo $entryCount; ?>)">Edit</button>
                <button class="cancel-button" type="button" onclick="cancelEditHistoryNote(<?php echo $entryCount; ?>)"
                  style="display:none;">Cancel</button>
                <button class="delete-button" type="submit" name="delete-history">Delete</button>
                <form action="" method="post">
                  <input type="hidden" name="history_id" value="<?php echo $historyEntry['history_id']; ?>">
                  <button class="save-button" type="submit" name="save-note" id="save-button-<?php echo $entryCount; ?>"
                    style="display:none;">Save</button>
                </form>
              </div>
            </div>
          </form>
        </div>
        <?php $entryCount++; // Increment the loop counter for the next entry ?>
      <?php } ?>
    </div>
  </div>

  <script>
    function editHistoryNote(entryCount) {
      const historyEntry = document.querySelector(`.history-entry-${entryCount}`);
      const editHistoryNoteTextarea = historyEntry.querySelector(`#edit-history-note-${entryCount}`);
      const editButton = historyEntry.querySelector(`.edit-button`);
      const saveButton = historyEntry.querySelector(`.save-button`);
      const deleteButton = historyEntry.querySelector(`.delete-button`);
      const cancelButton = historyEntry.querySelector(`.cancel-button`);

      // Display the textarea for editing and hide the current note
      editHistoryNoteTextarea.style.display = 'block';
      historyEntry.classList.add('editing');

      // Show the save and cancel buttons, hide the edit button
      saveButton.style.display = 'inline-block';
      cancelButton.style.display = 'inline-block';
      editButton.style.display = 'none';
      deleteButton.style.display = 'none';
    }

    function cancelEditHistoryNote(entryCount) {
      const historyEntry = document.querySelector(`.history-entry-${entryCount}`);
      const editHistoryNoteTextarea = historyEntry.querySelector(`.edit-history-note`);
      const editButton = historyEntry.querySelector(`.edit-button`);
      const saveButton = historyEntry.querySelector(`.save-button`);
      const cancelButton = historyEntry.querySelector(`.cancel-button`);
      const deleteButton = historyEntry.querySelector(`.delete-button`);

      // Hide the textarea for editing
      editHistoryNoteTextarea.style.display = 'none';
      historyEntry.classList.remove('editing');
      // Show the edit button, hide the save and cancel buttons
      saveButton.style.display = 'none';
      cancelButton.style.display = 'none';
      editButton.style.display = 'inline-block';
      deleteButton.style.display = 'inline-block';
    }
  </script>

</body>
</html> 