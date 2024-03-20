<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$m = new PHPMailer(true);

try {
    $m->isSMTP();                                            
    $m->Host      = 'smtp.gmail.com';                     
    $m->SMTPAuth = true;    
    $m->SMTPSecure = 'tls';                             
    $m->Username   = 'covisurance@gmail.com';                     
    $m->Password   = 'imfqdtfdtgkbqqbt';                               
    $m->Port = '587';   
    $m->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );                              
    $m->setFrom('covisurance@gmail.com', 'Covisurance');
    $m->isHTML(true);                                  
    $m->AltBody = 'Code Not Generated. Some error Occured';
} catch (Exception $e) {
    // Handle exception if any
    echo "Failed to initialize PHPMailer: " . $e->getMessage();
}
?>
