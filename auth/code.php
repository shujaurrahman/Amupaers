<?php
session_start();
if(isset($_SESSION['unverified'])){
  $msg="Your email is not verified. A code has been sent to your email. ";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/form.css">
  </head>
  <body>
  <?php
  require_once "../includes/header.php";
  ?>
    <section class="form login">
    <div id="login">
        <div id="formLogin">

            <h1>Verify Email →
            </h1>

          <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
          <div class="msg-text" style="display: <?php echo ($msg != '') ? 'block' : 'none'; ?>">
        <?php echo $msg; ?>
    </div>
                <div class="error-text"></div>
                  <div class="inputContainer">
                      <input type="text" class="inputLogin" placeholder=" " name="otp">
                      <label class="labelLogin">Verification Code</label>
                  </div>

                  <div class="field button">
                  <input type="submit" class="submitButton" value="Verify Code">
                  </div>
          </form>

            <div class="register">
            <span class="breaker">
                or 
            </span>    
                <a class="links" href="./register.php">Register</a>
            </div>
            <div class="register">
            <span class="breaker">
              <b>Fotgot password?</b>
            </span>  
            <a class="links" href="../auth/">Reset Now</a>
            </div>

        </div>

  </section>
  </div>
  </body>
  <script src="../js/verify.js"></script>
</html>