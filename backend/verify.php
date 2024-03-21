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

        // Echo "success" to indicate successful verification
        echo 'success';
    } else {
        // Echo the result (error message) returned by the verification method
        echo $result;
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method.';
}
?>
