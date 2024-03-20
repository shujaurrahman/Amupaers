<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page (change the URL as needed)
header("Location: http://localhost/amupapers/index.php");
exit;
?>
