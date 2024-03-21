<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/User.php';
require_once '../classes/Database.php';

session_start();

$database = new Database();
$conn = $database->getConnection();

if (isset ($_POST['email'], $_POST['password'])) {
    // Initialize User object
    $user = new User($conn);

    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email and password
    $errors = array();
    if (empty ($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty ($password)) {
        $errors[] = "Password is required.";
    }

    if (!empty ($errors)) {
        // Output validation errors
        foreach ($errors as $error) {
            echo $error;
        }
    } else {
        // Check if the email exists
        if (!$user->checkEmailExistence($email)) {
            echo "Email doesn't exist. Please register first.";
        } else {
            // Log in user
            $user->username = $email; // Assuming email is used as the username for login
            $user->password = $password;

            $loggedInUser = $user->loginUser();

            if ($loggedInUser === 'unverified') {
                // Resend verification email
                $verificationCode = $user->generateVerificationCode();
                $user->sendVerificationEmail($email, $verificationCode);

                // Update verification code in the database
                $user->updateVerificationCode($email, $verificationCode);
                $_SESSION['email'] = $email;
                // Send a specific response indicating that the verification code has been resent
                echo "resent";
            } elseif ($loggedInUser) {
                // Set email and username in session
                $_SESSION['email'] = $loggedInUser['email'];
                $_SESSION['username'] = $loggedInUser['username'];

                // Send a specific response indicating successful login
                echo "success";
            } else {
                echo "Invalid email or password.";
                // Or handle other error cases if needed
            }
        }
    }
} else {
    // If any necessary data is missing
    echo "Incomplete data provided.";
}
?>
