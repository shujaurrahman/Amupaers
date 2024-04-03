<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/User.php';
require_once '../classes/Database.php';

session_start();
$database = new Database();
$conn = $database->getConnection();

if (isset ($_POST['email'], $_POST['password'])) {
   
    $user = new User($conn);


    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $errors = array();
    if (empty ($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty ($password)) {
        $errors[] = "Password is required.";
    }

    if (!empty ($errors)) {
       
        foreach ($errors as $error) {
            echo $error;
        }
    } else {
        
        if (!$user->checkEmailExistence($email)) {
            echo "Email doesn't exist. Please register first.";
        } else {
            // Log in user
            $user->username = $email; 
            $user->password = $password;

            $loggedInUser = $user->loginUser();

            if ($loggedInUser === 'unverified') {
                // Resend verification email
                $verificationCode = $user->generateVerificationCode();
                $user->sendVerificationEmail($email, $verificationCode);

                // Update verification code in the database
                $user->updateVerificationCode($email, $verificationCode);
                $_SESSION['email'] = $email;
                $_SESSION['unverified']="unverified";
                // Send a specific response indicating that the verification code has been resent
                echo "resent";
            } elseif ($loggedInUser) {
                // Set email and username in session
                $_SESSION['email'] = $loggedInUser['email'];
                $_SESSION['username'] = $loggedInUser['username'];
                $_SESSION['wlcm-bck']="wc";
                $_SESSION['role']=""; 
                
                    // Check if the user is a superadmin
                        if ($loggedInUser['role'] == 'super_admin') {
                            // Set additional session data for superadmin
                            $_SESSION['role'] = 'super admin';
                        }
                        if ($loggedInUser['role'] == 'admin') {
                            // Set additional session data for superadmin
                            $_SESSION['role'] = 'admin';
                        }
                        // Redirect based on user role
                        if ($_SESSION['role'] == 'super admin' || $_SESSION['role'] == 'admin') {
                            echo "admin";
                        } else {
                            echo "success";
                        }
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
