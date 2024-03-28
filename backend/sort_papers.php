<?php
// Include the necessary classes and files
require_once '../backend/error.php';
require_once '../classes/database.php';
require_once '../classes/paper.php';

// Get the sorting parameter from the URL
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
$department = isset($_GET['department']) ? htmlspecialchars($_GET['department']) : '';
$course = isset($_GET['course']) ? htmlspecialchars($_GET['course']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'newest'; // Default sorting is by newest

// Create a new instance of the Database class
$database = new Database();

// Get the database connection
$conn = $database->getConnection();

// Initialize the Paper class with the database connection
$paper = new Paper($conn);

// Fetch papers based on the sorting parameter
switch ($sortBy) {
    case 'oldest':
        $papers = $paper->getPapersSortedByDateAndCategory($category, $department, $course, 'ASC');
        break;
    case 'popular':
        $papers = $paper->getPapersSortedByViewCountAndCategory($category, $department, $course);
        break;
    default:
        // Default sorting is by newest
        $papers = $paper->getPapersSortedByDateAndCategory($category, $department, $course, 'DESC');
}

// Prepare the response
$response = array('papers' => $papers);

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
