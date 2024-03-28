<?php

require_once "../backend/error.php";
require_once "../classes/database.php";
require_once "../classes/paper.php";

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {


    // Check if paper_id and status are set in the GET data
    if (isset($_GET["paper_id"]) && isset($_GET["status"])) {
        $paper_id = $_GET["paper_id"];
        $status = $_GET["status"];


        // Initialize Database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Initialize Paper object
        $paper = new Paper($conn);

        // Update paper status
        $result = $paper->updatePaperStatus($paper_id, $status);

        // Check if the update was successful
        if ($result) {
            // Status update successful
            session_start();
            $username = $_SESSION['username'];

            // Construct the message
            $message = "Paper with id $paper_id $status by $username... Reloading Page.!";
            echo json_encode(["success" => true, "message" => $message]);
        } else {

            // Status update failed
            echo json_encode(["success" => false, "error" => "Failed to update paper status"]);
        }
    } else {
        // Paper ID or status not provided in the request
        echo json_encode(["success" => false, "error" => "Paper ID or status not provided"]);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method not allowed"]);
}
?>
