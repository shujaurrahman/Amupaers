<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classes/database.php';
require_once '../classes/paper.php';

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Create a new Paper object with the database connection
$paper = new Paper($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["paper_id"])) {
    $paperId = $_POST["paper_id"];
    $table = 'papers';
    // Increment the view count for the paper with the given ID
    $paper->incrementViewCount($paperId);
    // You can also get the current view count if needed
    $viewCount = $paper->getViewCount($paperId);

    // Send a response indicating success
    http_response_code(200);
} else {
    // If the request method is not POST or the paper_id parameter is missing, return a bad request response
    http_response_code(400);
    echo "Bad request";
}
?>
