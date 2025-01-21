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

if (isset($_POST['feedback_name']) && isset($_POST['feedback_desc'])) {
    $acc_id = $_SESSION['acc_id'];
    $feedback_name = $_POST['feedback_name'];
    $feedback_desc = $_POST['feedback_desc'];

    $stmt = $conn->prepare("INSERT INTO feedback(feedback_name, feedback_desc, acc_id) VALUES(?, ?, ?)");
    $res = $stmt->execute([$feedback_name, $feedback_desc, $acc_id]);
    if ($res) {
        header("Location: feedback.php");
        exit();
    } else {
        echo 'error';
    }
}

if (isset($_POST['save-fb'])) {
    $feedback_id = $_POST['feedback_id'];
    $edited_feedback_desc = $_POST['edit-feedback-desc'];

    // Update the feedback description in the database
    $updateStmt = $conn->prepare("UPDATE feedback SET feedback_desc = ? WHERE feedback_id = ?");
    $updateRes = $updateStmt->execute([$edited_feedback_desc, $feedback_id]);

    if ($updateRes) {
        // Redirect to the feedback page after successful update
        header("Location: feedback.php");
        exit();
    } else {
        echo 'Error updating feedback.';
    }
}

if (isset($_POST['delete-fb'])) {
    $feedback_id = $_POST['feedback_id'];

    $stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
    $result = $stmt->execute([$feedback_id]);

    if ($result) {
        header("Location: feedback.php");
        exit(); 
    } else {
        echo 'error';
    }
}


$feedback = $conn->prepare("SELECT * FROM feedback WHERE acc_id = ? ORDER BY feedback_id");
$acc_id = $_SESSION['acc_id'];
$feedback->execute([$acc_id]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        function toggleEdit(entryCount) {
            const feedbackEntry = document.querySelector(`.feedback-entry-${entryCount}`);
            const editFeedbackDesc = feedbackEntry.querySelector(`.edit-feedback-desc`);
            const saveButton = feedbackEntry.querySelector(`.save-button`);
            const cancelButton = feedbackEntry.querySelector(`.cancel-button`);

            feedbackEntry.classList.add('editing');
            editFeedbackDesc.style.display = 'block';
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
        }

        function cancelEdit(entryCount) {
            const feedbackEntry = document.querySelector(`.feedback-entry-${entryCount}`);
            const editFeedbackDesc = feedbackEntry.querySelector(`.edit-feedback-desc`);
            const saveButton = feedbackEntry.querySelector(`.save-button`);
            const cancelButton = feedbackEntry.querySelector(`.cancel-button`);

            feedbackEntry.classList.remove('editing');
            editFeedbackDesc.style.display = 'none';
            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
        }
    </script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="feedback.css">

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
        <h2>Tell us what you think</h2>
    </div>
    <div class="space"></div>

    <div class=containerss>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="containertitle">
                <div class="forms">
                    <form action="" method="post">
                        <div class="input-field">
                            <input type="text" required placeholder="Title" id="feedback_name"
                                name="feedback_name"><br /><br />
                        </div>
                </div>
            </div>
            <div class="space"></div>
            <div class="containertxt">
                <div class="forms">
                    <form action="" method="post">
                        <div class="input-field">
                            <textarea required placeholder="Say something..." id="feedback_desc"
                                name="feedback_desc"></textarea>
                        </div>
                </div>
            </div>
            <div class="space"></div>

            <div class="input-field button">
                <button type="submit" id="postjournalbtn">Post</button>
            </div>
        </form>
    </div>

    <div class="content">
        <h1>Submitted feedback</h1>
    </div>

    <div class="container2">
        <div class="forms">
            <?php
                if ($feedback->rowCount() <= 0) {
                    echo "<br><div class='no-items'>
                    \t\tNo feedback submitted.
                    </div><style> padding-left: 50px;</style>";
                }
                $entryCount = 0; // Initialize a loop counter
            ?>
            <?php while ($submitfeedback = $feedback->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="submit-fb feedback-entry-<?php echo $entryCount; ?>">
                    <form action="" method="post" style="display: inline;">
                        <div class="feedback-header">
                            <h3>
                                <?php echo $submitfeedback['feedback_name']; ?><br>
                            </h3>
                            <h4>
                                <?php echo 'ID: ' . $submitfeedback['feedback_id']; ?><br>
                            </h4>
                            <div class="feedback-desc-container">
                                <div class="space"></div>
                                <p id="feedback-desc-<?php echo $entryCount; ?>" class="feedback-desc">
                                    <?php echo $submitfeedback['feedback_desc']; ?>
                                </p><br>
                                <textarea id="edit-feedback-desc-<?php echo $entryCount; ?>" name="edit-feedback-desc"
                                    class="edit-feedback-desc" rows="4"
                                    cols="50"><?php echo $submitfeedback['feedback_desc']; ?>
                                </textarea>
                            </div>
                            <small>
                                <?php echo $submitfeedback['feedback_date'] ?>
                            </small>
                            <button class="edit-button" type="button"
                                onclick="toggleEdit(<?php echo $entryCount; ?>)">Edit</button>
                            <button class="delete-button" type="submit" name="delete-fb"
                                id="delete-button-<?php echo $entryCount; ?>">Delete</button>
                            <button class="cancel-button" type="button" id="cancel-button"
                                onclick="cancelEdit(<?php echo $entryCount; ?>)">Cancel</button>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="feedback_id"
                                    value="<?php echo $submitfeedback['feedback_id']; ?>">
                                <button class="save-button" type="submit" name="save-fb"
                                    id="save-button-<?php echo $entryCount; ?>">Save</button>
                            </form>
                        </div>
                    </form>
                </div>
                <?php $entryCount++; // Increment the loop counter for the next entry ?>
            <?php } ?>
        </div>
    </div>
</body>

</html>