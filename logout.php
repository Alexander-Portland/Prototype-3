<?php
    //The code below is used to log the user out by reseting the session username and password to null and returning the user to the login page
    session_start();
    $_SESSION['username'] = "";
    $_SESSION['password'] = "";
    header('location:studentPage.php');

?>