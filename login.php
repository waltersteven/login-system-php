<?php
/* User login process, checks if user exists and password is correct */

//escaping email to protect against sql injection
$email = $mysqli->escape_string($_POST['email']);
$result = $mysqli->query("SELECT * FROM users WHERE email = '$email'");

if( $result->num_rows == 0){ //user dont exist
    echo "<span>user dont exist</span>";die;
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: error.php");
}else{

    $user = $result->fetch_assoc(); //para obtener un array asociativo de la fila obtenida en la consulta

    if(password_verify($_POST['password'], $user['password'])){ //comparando passwords
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['active'] = $user['active'];

        //this is how we'll know the user is logged ing
        $_SESSION['logged_in'] = true;

        header("location: profile.php");
    }else{ //passwords dont match
        
        $_SESSION['message'] = "You have entered wrong password, try again!";

        header("location: error.php");
    } 
}