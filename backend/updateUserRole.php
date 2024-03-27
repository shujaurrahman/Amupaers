<?php
// Start session to access session variables
session_start();

// Check if the user is logged in and is a super admin
$isSuperAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'super admin';

// Check if the user is a super admin
if (!$isSuperAdmin) {
    // Redirect unauthorized users to a login page or another appropriate page
    header("Location: ../login.php");
    exit; // Stop further execution
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user id and new role are set in the request
    if (isset($_POST["user_id"], $_POST["new_role"])) {
        // Sanitize user inputs
        $user_id = filter_var($_POST["user_id"], FILTER_SANITIZE_NUMBER_INT);
        $new_role = filter_var($_POST["new_role"], FILTER_SANITIZE_STRING);

        // Include necessary files
        require_once "../classes/User.php";
        require_once "../classes/Database.php";

        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Initialize User object
        $user = new User($conn);

        // Set user id and new role
        $user->id = $user_id;
        $user->role = $new_role;

        // Update user role
        if ($user->updateUserRole()) {
            // Redirect to a success page or display a success message
            header("Location: success.php");
            exit; // Stop further execution
        } else {
            // Handle update failure
            $error_message = "Failed to update user role.";
        }
    } else {
        // Handle missing parameters
        $error_message = "Missing parameters.";
    }
} else {
    // Handle non-POST requests
    $error_message = "Invalid request method.";
}

// Redirect to an error page or display the error message
header("Location: error.php?message=" . urlencode($error_message));
exit; // Stop further execution
?>
