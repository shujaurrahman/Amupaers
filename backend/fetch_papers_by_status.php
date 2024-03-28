<?php
// require_once "../backend/error.php"; // Uncomment if needed
require_once "../classes/paper.php";
require_once "../classes/Database.php";

// Check if the status parameter is provided
if (isset($_GET['status'])) {
    // Get the status from the request
    $status = $_GET['status'];

    // Initialize Database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Initialize Paper object
    $paper = new Paper($conn);

    // Fetch papers by status
    $papers = $paper->getPapersByStatus($status);

    // Check if there are any papers
    if (!empty($papers)) {
        // Send the papers as JSON response
        header('Content-Type: application/json');
        echo json_encode($papers);
    } else {
        // If no papers found for the given status
        echo json_encode(array('message' => 'No papers found for the specified status.'));
    }
} else {
    // If the status parameter is not provided
    echo json_encode(array('message' => 'Status parameter is missing.'));
}
?>
