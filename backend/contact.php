<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../classes/Database.php';

session_start();

$database = new Database();
$conn = $database->getConnection();

// Check if all necessary data is provided
if (isset($_POST['name'], $_POST['email'],$_POST['subject'], $_POST['message'])) {

    // Get the data from POST
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $subject=$_POST['subject'];
    $message = $_POST['message'];
    $date = date('Y-m-d H:i:s');

    // Prepare the SQL statement with named placeholders
    $sql = "INSERT INTO `testimonials`(`name`, `email`, `mobile_number`, `subject`,`message`, `submission_date`) VALUES (:name, :email, :phone, :subject, :message, :date)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':date', $date);

    // Execute the statement
    if ($stmt->execute()) {
        if ($_SESSION['msg'] = 'contact-msg'){
        // echo $_SESSION['msg'];
        echo "Message Submitted Succesfully.";
        }
    } else {
        echo "Failed to submit testimonial.";
    }

} 
?>
