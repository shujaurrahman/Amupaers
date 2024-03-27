<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the Paper class file
require_once '../classes/paper.php';
// Include the Database class file
require_once '../classes/database.php';

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Initialize the Paper class with your database connection
    $database = new Database();
    $conn = $database->getConnection();
    $paper = new Paper($conn);

    // Retrieve filter values from the GET request
    $category = $_GET['category'] ?? '';
    $department = $_GET['department'] ?? '';
    $course = $_GET['course'] ?? '';
    $year = $_GET['year'] ?? '';
    $otherFilter = $_GET['otherFilter'] ?? '';

    // Fetch papers based on the selected filters
    $papers = $paper->getPapersByCategoryDepartmentCourse($category, $department, $course);

    // Output the fetched papers
    if ($papers) {
        foreach ($papers as $paper) {
            // Extracting only the date part from the datetime string
            $uploadDate = date("F j, Y", strtotime($paper['upload_date']));
    
            echo "<li>
            <div class='category-card' onclick='openPDF(\"{$paper['file_path']}\", {$paper['id']})' title='Click to view paper'>
               <div>
                   <h3 class='h3 card-title'>
                       <a href='#'>{$paper['title']}</a>
                   </h3>
                   <span class='card-meta'>{$paper['course']}</span><br>
                   <span class='card-meta'>Year: {$paper['year']}</span><br>
                   <span class='card-meta'>Added on: {$uploadDate}</span>
               </div>
           </div>
       </li>";
   
        }
    } else {
        echo "No papers found.";
    }
} else {
    // If the request method is not GET, return an error
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
