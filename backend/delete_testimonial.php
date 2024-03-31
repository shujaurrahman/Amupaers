<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../classes/database.php';

$database = new Database();
$conn = $database->getConnection();

// Check if testimonial_id is provided
if (isset($_POST['testimonial_id'])) {
    // Prepare and execute the SQL statement to delete the testimonial
    $testimonial_id = $_POST['testimonial_id'];
    $sql = "DELETE FROM `testimonials` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$testimonial_id]);

    // Redirect back to the dashboard or any other page after deletion
    header("Location: ../admin/testimonials.php");
    exit();
} else {
    // If testimonial_id is not provided, redirect back to the dashboard or any other page with an error message
    header("Location: ../dashboard.php?error=1");
    exit();
}
