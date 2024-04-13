<?php
require_once '../classes/database.php';
require_once '../classes/paper.php';
require_once '../backend/error.php';

// Create a new instance of the Database class
$database = new Database();
$db = $database->getConnection();

// Create a new instance of the Paper class
$paper = new Paper($db);

// Fetch all available tags
$tags = $paper->getAllTags();

// Output unique tags as JSON
header('Content-Type: application/json');
echo json_encode($tags);
?>
