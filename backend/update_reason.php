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
        isset($_POST['paper_id'])
    ) {
        // Include the necessary files and initialize the database connection
        require_once "../classes/paper.php";
        require_once "../classes/database.php";

        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Initialize Paper object
        $paper = new Paper($conn);

        // Set paper properties
        $paper->id = $_POST['paper_id'];
        $paper->subject = $_POST['subject'];

        // Attempt to update the paper details
        if ($paper->updateReason()) {
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
