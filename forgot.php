<?php 
/* Reset your password form, sends reset.php password link */
require 'db.php';
require 'PHPMailer/PHPMailerAutoload.php';
session_start();

//Check if form submitted with method="post"
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $email = $mysqli->escape_string($_POST['email']);
  $result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");

  if($result->num_rows == 0){
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: error.php");
  }else{
    $user = $result->fetch_assoc();

    $email = $user['email'];
    $hash = $user['hash'];
    $first_name = $user['first_name'];
    
    //Session message to display on success.php
    $_SESSION['message'] = "<p>Please check your email <span>$email</span>"."for a confirmation link to complete your password reset!</p>";

    //send registration confirmation link (reset.php)
    $to = $email;
    $subject = 'Password reset link';
    $message_body = 'Hello '.$first_name.', you have requested password reset! 
    Please click this link to reset your password: 
    
    http://localhost:83/login-system/new/reset.php?email='.$email.'&hash='.$hash;    

    $mail = new PHPMailer();
    $mail->isSMTP();                            // Set mailer to use SMTP
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.gmail.com';             // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                     // Enable SMTP authentication
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = '465';                        // TCP port to connect to
    $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );                      
    $mail->isHTML(true);                        // Set email format to HTML
    $mail->Username = 'wltrst97@gmail.com';     // SMTP username
    $mail->Password = 'wltr/st97';              // SMTP password
    $mail->setFrom('wltrst97@gmail.com');
    $mail->addAddress($to);                     // Add a recipient

    $mail->Subject = $subject;
    $mail->Body    = $message_body;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        $_SESSION['message'] = 'email failed!';
        header("location: error.php");
    } else {
        echo '<span>Message has been sent</span>';
        header("location: success.php");
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Your Password</title>
  <?php include 'css/css.html'; ?>
</head>
<body>
    
  <div class="form">

    <h1>Reset Your Password</h1>

    <form action="forgot.php" method="post">
     <div class="field-wrap">
      <label>
        Email Address<span class="req">*</span>
      </label>
      <input type="email"required autocomplete="off" name="email"/>
    </div>
    <button class="button button-block"/>Reset</button>
    </form>
  </div>
          
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</body>

</html>
