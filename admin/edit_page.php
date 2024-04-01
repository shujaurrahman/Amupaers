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
function isSelected($value, $selectedValue)
{
    return $value == $selectedValue ? 'selected' : '';
}
// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize Paper object
$paper = new Paper($conn);

// Fetch paper details based on paper ID
$paperDetails = $paper->getPaperById($paperId); // Implement this method in the Paper class to fetch paper details by ID

// Check if paper details are fetched successfully
if ($paperDetails) {
    // Paper details fetched successfully, now populate the form fields with the fetched data
    $title = $paperDetails['title'];
    $category = $paperDetails['category'];
    $department = $paperDetails['department'];
    $course = $paperDetails['course'];
    $tags = $paperDetails['tags'];
    $year = $paperDetails['year'];
    // Other fields as needed


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Paper</title>
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
            body{
                min-height: 120vh;
            }
        </style>
    </head>

    <body>
        <?php require_once "../includes/adminNav.php"; ?>
        <br><br><br><br>

        <section class="form login">
            <div id="login">
                <div id="formLogin">
                    <h1>Edit Paper →</h1>
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
                            <input type="text" class="inputLogin" placeholder=" " name="title"
                                value="<?php echo $paperDetails['title']; ?>">
                            <label class="labelLogin">Title</label>
                        </div>
                        <div class="inputContainer">
                            <select name="category" class="inputLogin">
                                <option value="sessional" <?php echo isSelected('sessional', $paperDetails['category']); ?>>
                                    Sessional</option>
                                <option value="semester" <?php echo isSelected('semester', $paperDetails['category']); ?>>
                                    Semester</option>
                                <option value="entrance" <?php echo isSelected('entrance', $paperDetails['category']); ?>>
                                    Entrance</option>
                            </select>
                            <label class="labelLogin">Category</label>
                        </div>

                        <div class="inputContainer">
                            <select name="department" class="inputLogin" id="departmentSelect">
                                <!-- Display fetched department and all other available departments -->
                                <?php
                                foreach ($departmentCourses as $departmentName => $courses) {
                                    $selected = ($departmentName == $paperDetails['department']) ? 'selected' : '';
                                    echo "<option value='$departmentName' $selected>$departmentName</option>";
                                }
                                ?>
                            </select>
                            <label class="labelLogin">Department</label>
                        </div>
                        <div class="inputContainer">
                            <select name="course" class="inputLogin" id="courseSelect">
                                <!-- Display fetched course and all other available courses -->
                                <?php
                                $selectedDepartment = $paperDetails['department']; // Get the selected department from the paper details
                                $selectedCourse = $paperDetails['course']; // Get the selected course from the paper details
                                foreach ($departmentCourses as $departmentName => $courses) {
                                    if ($departmentName == $selectedDepartment) {
                                        foreach ($courses as $course) {
                                            $selected = ($course == $selectedCourse) ? 'selected' : '';
                                            echo "<option value='$course' $selected>$course</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <label class="labelLogin">Course</label>
                        </div>



                        <div class="inputContainer">
                            <input type="text" class="inputLogin" placeholder=" " name="tags"
                                value="<?php echo $paperDetails['tags']; ?>">
                            <label class="labelLogin">Tags (comma-separated)</label>
                        </div>
                        <div class="inputContainer">
                            <input type="number" class="inputLogin" placeholder=" " name="year"
                                value="<?php echo $paperDetails['year']; ?>">
                            <label class="labelLogin">Year</label>
                        </div>
                        <div class="field button">
                            <input type="submit" class="submitButton" value="Update Paper">
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <script src="../js/updatepaper.js"></script>

    </body>

    </html>

    <?php
} else {
    // Paper details not found or error occurred
    echo "<p>Error: Paper details not found.</p>";
}
?>