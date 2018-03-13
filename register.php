<?php
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */
require 'PHPMailer/PHPMailerAutoload.php';

 //Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];

//escape all $_POST variable to protect against SQL Injections. Se usa para crear una cadena SQL legal que se puede usar en una sentencia SQL.
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000)) );

//Checking if user with that email already exists
$result = $mysqli->query("SELECT * FROM users WHERE email = '$email'") or die($mysqli->error());

if( $result->num_rows > 0){
  $_SESSION['message'] = 'User with this email already exists!';
  header("location: error.php");
}else { //in case email doesn't exists in db
  $sql = "INSERT INTO users (first_name, last_name, email, password, hash) values ('$first_name','$last_name', '$email', '$password','$hash')";

  //adding user to the db
  if( $mysqli->query($sql)){
    $_SESSION['active'] = 0; //0 until user activates their account with verify.php
    $_SESSION['logged_in'] = true; //so we knoe the user has logged in
    $_SESSION['message'] = "Confirmation link has been sent to $email, please verify your account by clicking on the link in the message!";

    $to = $email;
    $subject = 'Account verification (app demo)';
    $message_body = 'Hello '.$first_name.', Thank you for signing up!

    Link to activate account:

    http://localhost:83/login-system/new/verify.php?email='.$email.'&hash='.$hash;
    
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
        header("location: profile.php");
    }
  }else {
    $_SESSION['message'] = 'Registration failed!';
    header("location: error.php");
  }

}


?>