<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
session_unset();
// Redirect to the login page (change the URL as needed)
header("Location: http://localhost/amupapers/index.php");
exit;
?>
