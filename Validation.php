<?php

session_start();


$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$name = $_POST['user'];
$pass = $_POST['password'];

if($name == ""){
    $_SESSION['username'] = "";
    $_SESSION['password'] = "";
    header('location:index.php');
}

//Form Start
$studentPick = "select forname, surname from studentdetails where student_username = '$name' && student_password = '$pass'";
$teacherPick = "select * from teacherdetails where teacher_username = '$name' && teacher_password = '$pass'";
$adminPick = "select admin_ID, forename, surname from admin where admin_username = '$name' && admin_password = '$pass' ";

$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

$resultTeacher = mysqli_query($con,$teacherPick);
$numTeacher = mysqli_num_rows($resultTeacher);

$resultadmin = mysqli_query($con,$adminPick);
$numAdmin = mysqli_num_rows($resultadmin);

//check
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

<html>
    <head>
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
        <section class = "centerPosClass">
            <section class = "helpContent">
            <form action="MessengerStudent.php">
                    <button class= "expandButton button">Retry</button>
                </form>
                <label class = "loginLabel">Login rejected</label>
                    <p>The login details you entered were incorrect</p>
            </section>
        </section>
    </main>
</html>