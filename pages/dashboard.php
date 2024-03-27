<?php
session_start();
$msgLong="";
// Check if the user is not logged in, then redirect them to the login page
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/amupapers/pages/login.php");
    exit();
}

$name=$_SESSION['username'];
// Check if a password change message is set in the session
if (isset($_SESSION['msg'])) {
    $msg = "Your password was changed";
    // Clear the session message to avoid displaying it again
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
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
          <h2 class="h2 section-title">Get Papers Department Wise</h2>

          <p class="section-text">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam expedita dignissimos omnis voluptatem inventore repellendus, 
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

                  <a href="#" class="btn-link">
                    <span class="span">Learn More</span>

                    <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                  </a>

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
                    <a href="papers.php?category=Semester" class="card-title">Endsems Papers</a>
                  </h3>

                  <p class="card-text">
             
                  </p>

                  <a href="#" class="btn-link">
                    <span class="span">Learn More</span>

                    <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                  </a>

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
                    <a href="papers.php?category=Sessional" class="card-title">Sessionals Papers</a>
                  </h3>

                  <p class="card-text">
            
                  </p>

                  <a href="papers.php?category=Sessional" class="btn-link">
                    <span class="span">Learn More</span>

                    <ion-icon name="arrow-forward" aria-hidden="true"></ion-icon>
                  </a>

                </div>

              </div>
            </li>

          </ul>
          <br>
          <div class="hero-content">

            <p class="section-subtitle">or Fetch All paper</p>

            <a href="" class="btn btn-primary">
              <span class="span">Click here</span>

              <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
            </a>

          </div>

        </div>
      </section>
  <script src="../js/script.js" defer></script>

</body>
</html>