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

    if ($result=="success") {
        echo 'success';
    } else {
        echo $result;
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method.';
}
?>
