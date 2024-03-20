<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/form.css">

</head>

<body>
<?php
  require_once "../includes/header.php";
  ?>

    <section class="form signup">
      <div id="login">
        <div id="formLogin">
          <h1>Register →
          </h1>
          <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="error-text"></div>
            <div class="inputContainer">
              <input type="text" class="inputLogin" placeholder=" " name="fname">
              <label class="labelLogin">First Name</label>
            </div>
            <div class="inputContainer">
              <input type="text" class="inputLogin" placeholder=" " name="lname">
              <label class="labelLogin">Last Name</label>
            </div>
            <div class="inputContainer">
              <input type="text" class="inputLogin" placeholder=" " name="email">
              <label class="labelLogin">Email</label>
            </div>
            <div class="inputContainer">
              <input type="password" class="inputLogin" placeholder=" " name="password">
              <label class="labelLogin">Password</label>
            </div>
            <div class="field button">
              <input type="submit" class="submitButton" value="Register Now">
            </div>
          </form>

          <div class="register">
            <span class="breaker">
              <b>Already signedup up?</b>
            </span>
            <a class="links" href="./login.php">login</a>
          </div>
        </div>
    </section>
</body>
<script src="../js/register.js"></script>

</html>