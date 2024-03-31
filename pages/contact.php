<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <?php require_once "../assets/fonts.php"?>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <?php 
    session_start();
    if (isset($_SESSION['msg'])) {
        $msg = "Message Submitted Successfully.";
        // echo $msg;
    }else{
        $msg="";
    }
    
    require_once "../includes/header.php";
    require_once "../backend/error.php";
    
    ?>

    <section class="form login">
        <div id="login">
            <div id="formLogin">

                <h1>Contact Us</h1>

                <form action="testimonial.php" method="POST" id="contactForm" class="mb-5" enctype="multipart/form-data" autocomplete="off">
                    <div class="error-text">
                        <?php echo $msg;?>
                    </div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="name" required>
                        <label class="labelLogin">Your name</label>
                    </div>
                    <div class="inputContainer">
                        <input type="email" class="inputLogin" placeholder=" " name="email" required>
                        <label class="labelLogin">Your Email</label>
                    </div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="phone" min="1" max="5" >
                        <label class="labelLogin">Mobile Number (Optional)</label>
                    </div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="subject" required>
                        <label class="labelLogin">Subject</label>
                    </div>
                    <div class="inputContainer">
                        <textarea class="inputLogin" name="message" placeholder=" " cols="30" rows="10" required></textarea>
                        <label class="labelLogin">Write your message</label>
                    </div>
                    <div class="field button">
                        <input type="submit" class="submitButton" value="Submit">
                    </div>
                </form>

            </div>
        </div>
    </section>
    <script src="../js/script.js" defer></script>
    <script src="../js/contact.js" defer></script>

</body>
</html>
