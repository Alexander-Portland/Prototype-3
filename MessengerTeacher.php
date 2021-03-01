<?php

session_start();

$nameCheck = $_SESSION['username'];

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' ";

$resultTeacher = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultTeacher);

$row = $resultTeacher->fetch_assoc();
$ID = $row['teacher_id'];
$Fname = $row['teacher_forname'];
$Lname = $row['teacher_surname'];


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

    <li><a href="teacherPage.php">Home</a></li>

    <li><a href = "logout.php">Log Out</a></li>
    
    </ul>
    <b class="outOfOrder">Out of Order! </b>
    </body>
</html>