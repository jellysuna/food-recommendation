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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['journal_desc']) && isset($_POST['journal_text'])) {
        $acc_id = $_SESSION['acc_id'];
        $journal_desc = $_POST['journal_desc'];
        $journal_text = $_POST['journal_text'];
        $journal_img = null; // Initialize journal_img variable

        // Check if an image is selected
        if ($_FILES['journal_img']['size'] > 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["journal_img"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if the file is an actual image
            $check = getimagesize($_FILES["journal_img"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["journal_img"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["journal_img"]["tmp_name"], $target_file)) {
                    $journal_img = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Insert the data into the database
        $stmt = $conn->prepare("INSERT INTO journal(journal_desc, journal_text, acc_id, journal_img) VALUES(?, ?, ?, ?)");
        $res = $stmt->execute([$journal_desc, $journal_text, $acc_id, $journal_img]);
        if ($res) {
            header("Location: journal.php");
            exit();
        } else {
            echo 'Error inserting data into the database.';
        }
    }
}


if (isset($_POST['save-fb'])) {
    $journal_id = $_POST['journal_id'];
    $edited_feedback_desc = $_POST['edit-feedback-desc'];

    $updateStmt = $conn->prepare("UPDATE journal SET journal_text = ? WHERE journal_id = ?");
    $updateRes = $updateStmt->execute([$edited_feedback_desc, $journal_id]);

    if ($updateRes) {
        header("Location: journal.php");
        exit();
    } else {
        echo 'Error updating entry.';
    }
}

if (isset($_POST['delete-fb'])) {
    $journal_id = $_POST['journal_id'];

    $stmt = $conn->prepare("DELETE FROM journal WHERE journal_id = ?");
    $result = $stmt->execute([$journal_id]);

    if ($result) {
        header("Location: journal.php");
        exit(); 
    } else {
        echo 'error';
    }
}


$journal = $conn->prepare("SELECT * FROM journal WHERE acc_id = ? ORDER BY journal_id");
$acc_id = $_SESSION['acc_id'];
$journal->execute([$acc_id]);
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
    <link rel="stylesheet" href="journal1.css">


    <title>Food Recommendation System</title>

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
        <h2>Foodie Diary</h2>
    </div>
    <div class="space"></div>

    <div class=containerss>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="containertitle">
                <div class="forms">
                    <form action="" method="post">
                        <div class="input-field">
                            <input type="text" required placeholder="Title" id="journal_desc"
                                name="journal_desc"><br /><br />
                        </div>
                </div>
            </div>
            <div class="space"></div>
            <div class="containertxt">
                <div class="forms">
                    <form action="" method="post">
                        <div class="input-field">
                            <textarea required placeholder="Say something..." id="journal_text"
                                name="journal_text"></textarea>
                        </div>
                </div>
            </div>
            <div class="input-field-image">
                <label for="journal_img">Upload Image:</label>
                <input type="file" id="journal_img" name="journal_img" accept="image/*">
            </div>

            <div class="space"></div>

            <div class="input-field button">
                <button type="submit" id="postjournalbtn">Post</button>
            </div>
        </form>
    </div>

    <div class="content">
        <h1>Recent journals</h1>
    </div>

    <div class="container2">
        <div class="forms">
            <?php
            if ($journal->rowCount() <= 0) {
                echo "<br><div class='no-items'>
                    \t\tNo journal entries.
                </div>";
            }
            $entryCount = 0; // Initialize a loop counter
            ?>
            <?php while ($submitjournal = $journal->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="submit-fb feedback-entry-<?php echo $entryCount; ?>">
                    <form action="" method="post" style="display: inline;">
                        <div class="feedback-header">
                            <h3>
                                <?php echo $submitjournal['journal_desc']; ?><br><br>
                            </h3>
                            <div class="feedback-desc-container">
                                <p id="feedback-desc-<?php echo $entryCount; ?>" class="feedback-desc">
                                    <?php echo $submitjournal['journal_text']; ?>
                                </p><br>
                                <textarea id="edit-feedback-desc-<?php echo $entryCount; ?>" name="edit-feedback-desc"
                                    class="edit-feedback-desc" rows="4"
                                    cols="50"><?php echo $submitjournal['journal_text']; ?></textarea>
                            </div>
                            <?php
                            if (!empty($submitjournal['journal_img'])) {
                                echo '<img src="' . $submitjournal['journal_img'] . '" alt="Journal Image" style="max-width: 100%; max-height: 200px;"><br>';
                            }
                            ?>
                            <small>
                                <?php echo $submitjournal['journal_date'] ?>
                            </small>
                            <button class="edit-button" type="button"
                                onclick="toggleEdit(<?php echo $entryCount; ?>)">Edit</button>
                            <button class="delete-button" type="submit" name="delete-fb"
                                id="delete-button-<?php echo $entryCount; ?>">Delete</button>
                            <button class="cancel-button" type="button" id="cancel-button"
                                onclick="cancelEdit(<?php echo $entryCount; ?>)">Cancel</button>
                            <form action="" method="post" style="display: inline;">
                                <input type="hidden" name="journal_id" value="<?php echo $submitjournal['journal_id']; ?>">
                                <button class="save-button" type="submit" name="save-fb"
                                    id="save-button-<?php echo $entryCount; ?>">Save</button>
                            </form>
                        </div>
                    </form>
                </div>
                <?php $entryCount++;  ?>
            <?php } ?>
        </div>
    </div>
</body>

</html> 