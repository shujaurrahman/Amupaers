<?php
// delete_paper.php

// Include the necessary files
require_once "../classes/Paper.php";
require_once "../classes/Database.php";

// Start the session
session_start();

// Check if the user is logged in and has the required role
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'super admin' && $_SESSION['role'] !== 'admin')) {
    // If the user is not authorized, return an error response
    $response = array(
        'success' => false,
        'message' => 'Unauthorized access'
    );
    http_response_code(403); // Forbidden
    echo json_encode($response);
    exit();
}

// Check if the paper ID is provided in the request
if (!isset($_GET['paper_id'])) {
    // If the paper ID is not provided, return an error response
    $response = array(
        'success' => false,
        'message' => 'Paper ID is missing'
    );
    http_response_code(400); // Bad request
    echo json_encode($response);
    exit();
}

// Get the paper ID from the request
$paperId = $_GET['paper_id'];

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize Paper object
$paper = new Paper($conn);

// Delete the paper
if ($paper->deletePaper($paperId)) {
    // If the deletion was successful, return a success response
    $response = array(
        'success' => true,
        'message' => 'Paper deleted successfully'
    );
    echo json_encode($response);
} else {
    // If the deletion failed, return an error response
    $response = array(
        'success' => false,
        'message' => 'Failed to delete paper'
    );
    http_response_code(500); // Internal server error
    echo json_encode($response);
}
?>
