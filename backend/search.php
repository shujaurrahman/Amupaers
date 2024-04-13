<?php
require_once "../classes/paper.php";
require_once "../classes/Database.php";
$database = new Database();
$conn = $database->getConnection();

// Example implementation using hardcoded data
$searchQuery = $_GET['query']; // Get search query from URL parameter

// Initialize Paper object
$paper = new Paper($conn);

// Fetch matching papers from the database using Paper class
$matchingPapers = $paper->searchPapers($searchQuery);

// Output search results as JSON
header('Content-Type: application/json');
echo json_encode($matchingPapers);
?>
