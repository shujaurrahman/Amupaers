<?php
session_start();

// Check if the user is logged in and has the role of "super_admin" or "admin"
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'super admin' && $_SESSION['role'] !== 'admin')) {
    // If not logged in or doesn't have the required role, return an error message
    http_response_code(403); // Forbidden
    echo "Error: Unauthorized access.";
    exit;
}

// Check if the request is sent via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary data is received
    if (
        isset($_POST['title']) && isset($_POST['category']) && isset($_POST['department']) &&
        isset($_POST['course']) && isset($_POST['tags']) && isset($_POST['year']) &&
        isset($_POST['paper_id'])
    ) {
        // Include the necessary files and initialize the database connection
        require_once "../backend/error.php";
        require_once "../classes/paper.php";
        require_once "../classes/database.php";

        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Initialize Paper object
        $paper = new Paper($conn);

        // Set paper properties
        $paper->id = $_POST['paper_id'];
        $paper->title = $_POST['title'];
        $paper->category = $_POST['category'];
        $paper->department = $_POST['department'];
        $paper->course = $_POST['course'];
        $paper->tags = $_POST['tags'];
        $paper->year = $_POST['year'];

        // Get admin email from session
        $adminEmail = $_SESSION['email'];

        // Concatenate "uploaded by" user email and "updated by" admin email
        $paper->updated_by ="updated by: ".$adminEmail;

        // Attempt to update the paper details
        if ($paper->updatePaper()) {
            // Paper details updated successfully
            echo "success";
        } else {
            // Failed to update paper details
            echo "Error: Failed to update paper details.";
        }
    } else {
        // Required data not received
        echo "Error: Incomplete data received.";
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo "Error: Invalid request method.";
}
?>
