<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Database.php';

$db = new Database();
$conn = $db->getConnection();
if (isset ($_POST['email'])) {

    $user = new User($conn);

    $email = $_POST['email'];

    // Check if the email exists in the database
    $userData = $user->loginWithEmailExistence($email);

    if ($userData) {
        // Generate verification code
        $verificationCode = $user->generateVerificationCode();
        
        // Send verification email
        $user->sendVerificationEmail($email, $verificationCode);
        
        // Update verification code in the database
        $user->updateVerificationCode($email, $verificationCode);

        // Set email in session for further processing
        $_SESSION['email'] = $email;
        $_SESSION["msg"]="pass-reset";

        // Send success response to the client
        echo 'success';
    } else {
        // If email does not exist, return an error message
        echo 'Email not found.';
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method.';
}
?>
