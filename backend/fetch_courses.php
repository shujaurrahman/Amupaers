<?php
// Include the Database class file
require_once '../classes/database.php';

// Include the Paper class file
require_once '../classes/paper.php';

// Check if the department parameter is set in the request
if (isset($_GET['department'])) {
    // Get the department from the request
    $department = htmlspecialchars($_GET['department']);

    // Create a new instance of the Database class
    $database = new Database();

    // Get the database connection
    $conn = $database->getConnection();

    // Initialize the Paper class with database connection
    $paper = new Paper($conn);

    // Get the category from the URL parameter
    $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';

    // Fetch courses based on the selected department and category
    $courses = $paper->getCoursesByDepartment($category, $department);

    // Prepare the response as JSON
    $response = array('courses' => $courses);

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If department parameter is not set, return an error
    http_response_code(400);
    echo json_encode(array('error' => 'Department parameter is missing'));
}
?>
