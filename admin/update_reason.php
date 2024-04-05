<?php

$paperId = $_GET['paper_id']; //  via GET parameter

require_once "../classes/paper.php";
require_once "../classes/Database.php";
require_once "../includes/departments.php";

session_start();

// Check if the user is logged in and has the role of "super_admin" or "admin"
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'super admin' && $_SESSION['role'] !== 'admin')) {
    header("location: ../index.php");
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Initialize Paper object
$paper = new Paper($conn);

// Fetch paper details based on paper ID
$paperDetails = $paper->getPaperById($paperId);

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Reason for Rejecting paper</title>
        <?php require_once "../assets/fonts.php" ?>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/form.css">
        <style>
            #login #formLogin {
                padding: 0px 0px;
            }

            #login #formLogin .inputContainer .inputLogin {
                font-size: 14px;
            }

            .login {
                margin-top: 50px;
            }

        </style>
    </head>

    <body>
        <?php require_once "../includes/adminNav.php"; ?>
        <br><br><br><br>

        <section class="form login">
            <div id="login">
                <div id="formLogin">
                    <h1>Reason for Rejecting Paper →</h1>
                    <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                        
                        <div class="error-text"></div>
                        <div class="inputContainer">
    <input type="text" class="inputLogin" placeholder=" " name="paper_id"
        value="<?php echo $paperDetails['id']; ?>" readonly>
    <label class="labelLogin">Id {Read Only}</label>
</div>
                        <div class="inputContainer">
    <input type="text" class="inputLogin" placeholder=" " name="uploaded_by"
        value="<?php echo $paperDetails['uploaded_by']; ?>" readonly>
    <label class="labelLogin">Uploaded by {Read Only}</label>
</div>

<div class="inputContainer">
                            <input type="text" class="inputLogin" placeholder=" " name="subject"
                                >
                            <label class="labelLogin">Write Reason</label>
                        </div>
                        <div class="field button">
                            <input type="submit" class="submitButton" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <script src="../js/updatereason.js"></script>

    </body>

    </html>
