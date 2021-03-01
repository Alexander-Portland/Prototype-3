<?php

session_start();

$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$studentPick = "select student_id, forname, surname from studentdetails where student_username = '$nameCheck' && student_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

$row = $resultStudent->fetch_assoc();
$ID = $row['student_id'];
$Fname = $row['forname'];
$Lname = $row['surname'];

if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>

<html>
    <head>
    <title>Student Page</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>
    </head>
    <body>
    <ul>

    <li><p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p></li>

    <li><a href="studentPage.php">Home</a></li>

    <li><a href = "logout.php">Log Out</a></li>
    
    </ul>

    <b class="outOfOrder">Out of Order! </b>
    </body>
</html>