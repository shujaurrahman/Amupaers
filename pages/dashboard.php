<?php
session_start();

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php
  require_once "../includes/header.php";
  ?>
  <br><br><br><br>
  <!-- <h2>Hello, Shuja ur Rahman </h2> -->
        <section class="section category" aria-label="category">
        <div class="container">
        <div class="msg-text" style="display: <?php echo ($msg != '') ? 'block' : 'none'; ?>">
        <?php echo $msg; ?>
    </div>
          <p class="section-subtitle">Trending</p>

          <h2 class="h2 section-title">Popular Papers To Solve </h2>

          <ul class="grid-list">

            <li>
              <div class="category-card">

                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Personal Development</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">

                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Human Research</a>
                  </h3>

                  <span class="card-meta">24 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">


                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Art & Design</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">

                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Business Management</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">

                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Web Development</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">

                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Lifestyle</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">


                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Digital Marketing</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">



                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Data Sciences</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

            <li>
              <div class="category-card">



                <div>
                  <h3 class="h3 card-title">
                    <a href="#">Health & Fitness</a>
                  </h3>

                  <span class="card-meta">39 Course</span>
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>
      <?php
    require_once "../includes/footer.php";
    ?>
  <script src="../js/script.js" defer></script>

</body>
</html>