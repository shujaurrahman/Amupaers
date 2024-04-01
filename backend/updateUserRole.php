<?php
// Include necessary files
require_once "../classes/User.php";
require_once "../classes/Database.php";

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Extract user ID and new role from the URL query parameters
    $userId = $_GET['userId'];
    $newRole = $_GET['newRole'];

    // Initialize Database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Initialize User object
    $user = new User($conn);

    // Update the user's role in the database
    if ($user->updateUserRole($userId, $newRole)) {
        // If role update is successful, return success response
        echo json_encode(array("success" => true));
    } else {
        // If role update fails, return error response
        echo json_encode(array("success" => false, "message" => "Failed to update user role."));
    }
} else {
    // If the request method is not GET, return error response
    echo json_encode(array("success" => false, "message" => "Invalid request method."));
}

?>
