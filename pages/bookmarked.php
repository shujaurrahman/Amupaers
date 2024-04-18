<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bookmarked</title>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz' crossorigin='anonymous'></script>
<link rel="stylesheet" href="../css/style.css">
<?php require_once "../assets/fonts.php";
      require_once "../classes/paper.php";
      require_once "../classes/database.php";
?>
<style>
          :root{
        --section-padding: 50px !important;
    }
</style>
</head>
<body>
<?php
  require_once "../includes/header.php";

session_start();

// Check if the user is not logged in, then redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/amupapers/pages/login.php");
    exit();
}

$name=$_SESSION['username'];
$email=$_SESSION['email'];
echo "<style>tr{
    border: 2px solid !important;
    border-color: var(--green) !important ;
}
td,th{
    padding-left: 10px !important;

}
.btn-group .btn {
margin-right: 10px; /* Adjust the value as needed */
}
.btn {
min-height: 25px;
max-width: max-content;
font-size: var(--fs-5);
font-weight: var(--fw-700);
display: flex;
align-items: center;
gap: 5px;
padding: 8px 15px;
border-radius: var(--radius-6);
transition: var(--transition-1);
margin-bottom: 12px;

}
.btn:hover{
background-color: var(--green);
color: white;
}
.btn.active{
background-color: var(--green);
color: white;
}
span a:hover,.ion-icon-container:hover {
color: var(--ultramarine-blue) !important ;
}

.error-text {
color: #fff;
/* color: var(--ultramarine-blue); */
padding: 5px 15px;
text-align: center;
border-radius: 6px;
background: var(--ultramarine-blue);
border: 1px solid #f5c6cb;
margin-bottom: 20px;
display: none;
width: 50rem;
}
.container-fluid{
    padding-top:100px !important;;
}
</style>";

// Initialize Database connection
$database = new Database();
$conn = $database->getConnection();

// Initialize User object
$paper = new Paper($conn);

?>


    <div class="container">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3" style="padding:20px 30px">Comming Soon! </h1>
        </div>
    </div>
    <?php

?>
</body>
</html>