<?php
/* Displays user information and some useful messages */
session_start();

//Check is user is logged in using the session variable
if($_SESSION['logged_in'] != 1){
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");
}else {
  $first_name = $_SESSION['first_name'];
  $last_name = $_SESSION['last_name'];
  $email = $_SESSION['email'];
  $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">

          <h1>Welcome</h1>
          
          <p>
          <?php 
          // Display message about account verification link only once
          if( isset($_SESSION['message'])){
            echo $_SESSION['message'];

            //dont annoy the user with more message upon page refresh
            unset($_SESSION['message']); //to remove message session variable
          }
          ?>
          </p>
          
          <?php
          // Keep reminding the user this account is not active, until they activate
          if( !$active){ //in case the account hasn't been activated yet.
            echo 
            '<div class="info">
            Account is unverified, please confirm your email by clicking on the email link!
            </div>';
          }
          ?>
          
          <h2><?php echo $first_name.' '.$last_name; ?></h2>
          <p><?= $email ?></p>
          
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>

    </div>
    
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
