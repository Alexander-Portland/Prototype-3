<?php

session_start();

//For test purposes the database login was set to factory default
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//the username and password of the login is pulled from the index page
$name = $_POST['user'];
$pass = $_POST['password'];

//attempts to gain access via the url without a session login will result in the user being rediected to login page
if($name == ""){
    $_SESSION['username'] = "";
    $_SESSION['password'] = "";
    header('location:index.php');
}

//Form Start: login details are then checked against all account databases
$studentPick = "select forname, surname from studentdetails where student_username = '$name' && student_password = '$pass'";
$teacherPick = "select * from teacherdetails where teacher_username = '$name' && teacher_password = '$pass'";
$adminPick = "select admin_ID, forename, surname from admin where admin_username = '$name' && admin_password = '$pass' ";

$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

$resultTeacher = mysqli_query($con,$teacherPick);
$numTeacher = mysqli_num_rows($resultTeacher);

$resultadmin = mysqli_query($con,$adminPick);
$numAdmin = mysqli_num_rows($resultadmin);

//Check start: login queries are checked to see if any of the databases returned an existing account
//If accounts check out as true then the user is sent to their account homepage
if($numStudent == 1){
    $_SESSION['username'] = $name;
    $_SESSION['password'] = $pass;
    header('location:studentPage.php');
}
elseif($numTeacher == 1){
    $_SESSION['username'] = $name;
    $_SESSION['password'] = $pass;
    header('location:teacherPage.php');
}

elseif($numAdmin == 1){
    $_SESSION['username'] = $name;
    $_SESSION['password'] = $pass;
    header('location:administration.php');
}
?>

<!--If the login is rejected the user will be displayed this section of code -->
<html>
    <head>
        <!--The rejection notice is linked to the same css and javascript page as the index page -->
        <title>Login rejected</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
        </head>
    </head>
    <main>
        <!--The rejection notice contains a title and a description notifying the user of the issue-->
        <section class = "centerPosClass">
            <section class = "helpContent">
            <!--The rejection page contains a retry button to return the user to the login page -->
            <form action="MessengerStudent.php">
                    <button class= "expandButton button">Retry</button>
                </form>
                <label class = "loginLabel">Login rejected</label>
                    <p>The login details you entered were incorrect</p>
            </section>
        </section>
    </main>
</html>