<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['email'])) {
        echo 'User not logged in.';
        exit; // Stop further execution
    }

    // Validate form data
    if (isset($_POST['password'], $_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Perform validation checks
        if (empty($password) || empty($confirmPassword)) {
            echo 'Password fields cannot be empty.';
            exit;
        }
        if ($password !== $confirmPassword) {
            echo 'Passwords do not match.';
            exit;
        }
        // Password validation rules can be added here if needed

        // Proceed to update password
        $db = new Database();
        $conn = $db->getConnection();

        $user = new User($conn);

        // Set the email from the session
        $userEmail = $_SESSION['email'];

        // Update password
        $user->email = $userEmail;
        $user->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        if ($user->updatePassword()) {
            echo 'success'; // Password updated successfully
        } else {
            echo 'Failed to update password.';
        }
    } else {
        echo 'Fill both entries.';
    }
} else {
    echo 'Invalid request method.';
}
?>
