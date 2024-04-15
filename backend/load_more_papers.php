<?php
require_once '../classes/database.php';
require_once '../classes/paper.php';

$database = new Database();
$conn = $database->getConnection();
$paper = new Paper($conn);

// Retrieve offset and limit from the GET request
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9;

// Fetch papers from the database based on offset and limit
$papers = $paper->getMorePapers($offset, $limit);

// Output papers as JSON
echo json_encode($papers);
?>

