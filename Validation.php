<?php

session_start();


$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$name = $_POST['user'];
$pass = $_POST['password'];

$studentPick = "select forname, surname from studentdetails where student_username = '$name' && student_password = '$pass'";
$teacherPick = "select * from teacherdetails where teacher_username = '$name' && teacher_password = '$pass'";

$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

$resultTeacher = mysqli_query($con,$teacherPick);
$numTeacher = mysqli_num_rows($resultTeacher);

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

else{
    header('location:index.php');
}
?>