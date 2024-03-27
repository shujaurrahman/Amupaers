<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classes/database.php'; // Include the database class file
require_once '../classes/paper.php'; // Include the Paper class file
session_start();
// Create a new instance of the Database class
$database = new Database();

// Get the database connection
$conn = $database->getConnection();

// Create a new instance of the Paper class
$paper = new Paper($conn);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $department = $_POST['department'];
    $course = $_POST['course'];
    $tags = $_POST['tags'];
    $year = $_POST['year'];
    $pdfFile = $_FILES['pdfFile'];
    $uploaded_by = $_SESSION['email']; // Get email from session
    
    // Set file upload directory
    $targetDirectory = "../uploads/";

    // Get the file name
    $fileName = basename($pdfFile['name']);
    
    // Generate a unique file name using timestamp
    $uniqueFileName = uniqid() . '_' . $fileName;
    
    // Set file path
    $targetFilePath = $targetDirectory . $uniqueFileName;
    
    // Check if file is selected
    if (!empty($fileName)) {
        // Move the file to the upload directory
        if (move_uploaded_file($pdfFile['tmp_name'], $targetFilePath)) {
            // Add paper to the database
            $paper->title = $title;
            $paper->category = $category;
            $paper->department = $department;
            $paper->course = $course;
            $paper->subject = null; // Subject will be set later if paper is rejected
            $paper->tags = $tags;
            $paper->year = $year;
            $paper->file_path = $targetFilePath;
            $paper->uploaded_by = $_SESSION["email"];
            
            // Add the paper to the database
            if ($paper->addPaper()) {
                $_SESSION['p_name']=$title;
                $_SESSION['p_cat']=$category;
                $_SESSION['p_dept']=$department;
                $_SESSION['p_course']=$course;
                // $email=$_SESSION[]
                $_SESSION['p_success']="Paper with title \"$title\" for course \"$course\" of Department \"$department\" added succesfully by {$_SESSION['email']}.";
                $_SESSION['tk']="Thankyou, for your support.";
                echo "success"; // Return success message
            } else {
                echo "Failed to add paper."; // Return failure message
            }
        } else {
            echo "Error uploading file."; // Return file upload error message
        }
    } else {
        echo "No file selected."; // Return message if no file is selected
    }
}
?>
