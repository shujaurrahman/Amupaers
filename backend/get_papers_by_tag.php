<?php
require_once '../classes/database.php';
require_once '../classes/paper.php';

// Check if tag parameter is provided
if(isset($_GET['tag'])) {
    // Get tag from the request
    $tag = $_GET['tag'];

    // Create a new instance of the Database class
    $database = new Database();
    $db = $database->getConnection();

    // Create a new instance of the Paper class
    $paper = new Paper($db);

    // Fetch papers by tag
    $papers = $paper->getPapersByTag($tag);

    // Output papers as JSON
    header('Content-Type: application/json');
    echo json_encode($papers);
} else {
    // If tag parameter is not provided, return error
    http_response_code(400);
    echo json_encode(array("message" => "Tag parameter is missing."));
}
?>
