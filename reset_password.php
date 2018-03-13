<?php
/* Password reset process, updates database with new user password */
require 'db.php';
session_start();

//make sure the form is being submitted with method="post"
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //make sure the two passwords match
    if($_POST['newpassword'] == $_POST['confirmpassword']){
        $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);

        //we get $_POST['email'] and $_POST['hash'] from the hidden input field of reset.php form
        $email = $mysqli->escape_string($_POST['email']);
        $hash = $mysqli->escape_string($_POST['hash']);

        $sql = "UPDATE users SET password='$new_password', hash='$hash' WHERE email='$email'";

        if($mysqli->query($sql)){
            $_SESSION['message'] = "Your password has been reset successfully!";
            header("location: success.php");
        }
    }else{
        $_SESSION['message'] = "Two passwords must match, try again!";
        header("location: error.php");
    }
}

?>