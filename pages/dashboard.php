<?php
session_start();
$msgLong="";
// Check if the user is not logged in, then redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/amupapers/pages/login.php");
    exit();
}

$name=$_SESSION['username'];
$email=$_SESSION['email'];
// $email="shuja";

// Check if a password change message is set in the session
if (isset($_SESSION['msg'])) {
    $msg = "Your password was changed";
    unset($_SESSION['msg']);
} 
elseif(isset($_SESSION['unverified'])){
   $msg="Account Verification Successfull.  Welcome,$name";
   unset($_SESSION['unverified']);
}
elseif(isset($_SESSION['wlcm-msg'])){
   $msg="Welcome,$name";
   unset($_SESSION['wlcm-msg']);
}
elseif(isset($_SESSION['wlcm-bck'])){
   $msg="Welcome Back,$name";
   unset($_SESSION['wlcm-bck']);
}
else {
   $msg = ""; // Set empty message if not set
}
if (isset($_SESSION['p_success']) && isset($_SESSION['p_name'])&& isset($_SESSION['p_cat'])&& isset($_SESSION['p_course']) && isset($_SESSION['tk'])) {
  $msgLong = $_SESSION['p_success'];
  $msg=$_SESSION['tk'];
  unset($_SESSION['p_success']);
  unset($_SESSION['tk']);
}
require_once "../classes/database.php";
require_once "../classes/paper.php";
require_once "../classes/user.php";
// update session code to verified 
$database = new Database();
$conn = $database->getConnection();
$user=new User($conn);
$userDetails = $user->getUserDetailsByEmail($email);
$_SESSION['code']=$userDetails['code'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>


    <link rel="stylesheet" href="../css/style.css">
    <?php require_once "../assets/fonts.php"?>
    <style>
              :root{
            --section-padding: 50px !important;
        }
    </style>
</head>

<body>
<?php
  require_once "../includes/header.php";
  ?>
  <br><br><br><br>
      <section class="section service" id="services">
        <div class="container">
          <div class="msg-long" style="display: <?php echo ($msgLong != '') ? 'block' : 'none'; ?>">
            <?php echo $msgLong; ?>
          </div>
          <div class="msg-text" style="display: <?php echo ($msg != '') ? 'block' : 'none'; ?>">
          <?php echo $msg; ?>
          </div>
          <h2 class="h2 section-title">Select Category</h2>

          <p class="section-text">
          Continue by exploring categories and selecting departments & courses to find papers tailored to your interests.<br> Don't forget to provide your genuine <a href="mailto:shujaurrehman210@gmail.com" style="color:var(--ultramarine-blue)">feedback</a>! Your insights help us enhance the user experience.
          </p>

          <ul class="service-list">

            <li>
              <div class="service-card">

                <figure class="card-banner">
                  <img src="../assets/images/service-1.gif" width="728" height="344" loading="lazy" alt="support"
                    class="w-100">
                </figure>

                <div class="card-content">

                  <h3 class="h3">
                  <a href="papers.php?category=Entrance" class="card-title">Entrance Papers</a>
                  </h3>

                  <p class="card-text">
                    
                  </p>

                </div>

              </div>
            </li>

            <li>
              <div class="service-card">

                <figure class="card-banner">
                  <img src="../assets/images/service-2.gif" width="728" height="344" loading="lazy" alt="Engagement"
                    class="w-100">
                </figure>

                <div class="card-content">

                  <h3 class="h3">
                    <a href="papers.php?category=Semester" class="card-title">Semester Papers</a>
                  </h3>

                  <p class="card-text">
             
                  </p>

                </div>

              </div>
            </li>

            <li>
              <div class="service-card">

                <figure class="card-banner">
                  <img src="../assets/images/service-3.gif" width="728" height="344" loading="lazy" alt="Marketing"
                    class="w-100">
                </figure>

                <div class="card-content">

                  <h3 class="h3">
                    <a href="papers.php?category=Sessional" class="card-title">Sessional Papers</a>
                  </h3>

                  <p class="card-text">
            
                  </p>

                </div>

              </div>
            </li>

          </ul>
          <br>
<?php


// Initialize User object
$paper = new Paper($conn);

// Fetch all users
$papers = $paper->getPapersByUser($email);
if (!empty($papers)){

?>
          <div class="hero-content">
          <a href="user_papers_table.php" class="btn btn-primary" style="min-height: 0; padding: 7px 12px;gap: 0px;font-weight:var(--fw-500); margin-top:20px !important;">
            <span class="span">Your Uploads</span>
            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </a>
<?php
}
?>
          </div>

        </div>
      </section>
  <script src="../js/script.js" defer></script>

</body>
</html>