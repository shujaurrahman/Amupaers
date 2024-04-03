<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/User.php';
require_once '../classes/Database.php';

session_start();

$database = new Database();
$conn = $database->getConnection();

// Check if all necessary data is provided
if (isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['password'])) {
    // Initialize User object
    $user = new User($conn);

    // Validate data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user data
    $errors = $user->validateUserData($fname, $lname, $email, $password);

    if (!empty($errors)) {
        // Output validation errors
        foreach ($errors as $error) {
            echo $error;
        }
    } else {
        // Check if email already exists
        if ($user->checkEmailExistence($email)) {
            echo "This email is already registered. Please use a different email address.";
        } else {
            // Proceed with user registration
            // Set properties with form data
            $user->username = $fname . ' ' . $lname;
            $user->email = $email;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->role = 'user'; // Set default role to user
            $user->registration_date = date('Y-m-d H:i:s');
            $user->is_super_admin = 0;

            // Attempt to register the user
            $verificationCode = $user->registerUser();
            if ($verificationCode) {
                // Set email and username in session
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $user->username;
                $userDetails = $user->getUserDetailsByEmail($email);
                $_SESSION['code']=$userDetails['code'];

                $subject = 'Email Verification Code';
                $msg = 'Thank you for registering. Your verification code is:';
                $msgend = 'Please use this code to verify your email address.';

                // Send email using the PHPMailer instance created in mail.php
                try {
                    require "../auth/mail.php";
                    $m->addAddress($email, $fname); // Add recipient
                    $m->Subject = $subject;
                    $m->Body = $msg . ' <b>' . $verificationCode . '</b><br><br>' . $msgend; // Set email body
                    $m->send(); // Send the email
                    echo "success";
                } catch (Exception $e) {
                    echo "Failed to send verification email. Error: " . $e->getMessage();
                }
            }
        }
    }
} else {
    // If any necessary data is missing
    echo "Incomplete data provided.";
}
?>
