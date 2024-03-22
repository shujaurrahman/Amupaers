<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    $user = new User($conn);

    $otp = $_POST['otp'];
    $userEmail = $_SESSION['email']; 

    $result = $user->verifyUser($otp, $userEmail);

    if ($result === "success") {
        // Get the user's details from the database
        $userDetails = $user->getUserDetailsByEmail($userEmail);

        // Set the username and email in the session
        $_SESSION['email'] = $userDetails['email'];
        $_SESSION['username'] = $userDetails['username'];

        // Check if session variable "msg" is set to "pass-reset"
        if (isset($_SESSION['msg']) && $_SESSION['msg'] === "pass-reset") {
            echo 'newpassword'; // Echo "pass-reset" if session variable is set to "pass-reset"
        } else {
            $_SESSION['wlcm-msg']="wlcm-msg";
            echo 'success'; // Echo "success" if session variable is not set or has a different value
        }
    } else {
        // Echo the result (error message) returned by the verification method
        echo $result;
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method.';
}
?>
