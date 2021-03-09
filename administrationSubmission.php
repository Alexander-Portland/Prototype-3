<?php

session_start();

$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$teacherPick = "select admin_ID, forename, surname from admin where admin_username = '$nameCheck' && admin_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultStudent);

$row = $resultStudent->fetch_assoc();
$ID = $row['admin_ID'];
$Fname = $row['forename'];
$Lname = $row['surname'];

if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}

$dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

if(isset($_POST['sendNewClass'])){
    $classTitle = htmlspecialchars($_POST['classNameInput'],ENT_COMPAT);
    $classDescription = htmlspecialchars($_POST['classDescriptionInput'],ENT_COMPAT);

    $classQuery = "select * from classdetails where class_title = '$classTitle'";
    $resultclass = mysqli_query($con,$classQuery);
    $numclass = mysqli_num_rows($resultclass);

    if($numclass >= 1){
        echo "<script type='text/javascript'>alert('Adding class aborted, you cannot add a class with the same title as a another class');</script>";
        header("Refresh:0; administration.php");
    }
    else{
        $stmt = $dbh->prepare("insert into classdetails values('',?,?)");
        $stmt->bindParam(1,$classTitle);
        $stmt->bindParam(2,$classDescription);
        $stmt->execute();

        header("Refresh:0; administration.php");
    }
    }

if(isset($_POST['btnDelete'])){
    $deleteID = $_POST['classDeleteID'];
    $deleteFind = "select class_id from classdetails where class_id = '$deleteID'";
    $resultClassFind = mysqli_query($con,$deleteFind);
    $numDeleteResult = mysqli_num_rows($resultClassFind);
    if($numDeleteResult == 1){
        $classDelete = "delete from classdetails where class_id = '$deleteID'";
        $classDeleteQuery = mysqli_query($con,$classDelete);
        header("Refresh:0; administration.php");
    }
    else{
        echo "<script type='text/javascript'>alert('Deletion failed to process');</script>";
        header("Refresh:0; administration.php");
    }
}

if(isset($_POST['search'])){
    $classID = $_POST['classUpdateID'];
    $classTitleSearch = htmlspecialchars($_POST['classUpdate'],ENT_COMPAT);
    $classDescriptionSearch = htmlspecialchars($_POST['classDescriptionUpdate'],ENT_COMPAT);
    $classFindSearch = "select class_id from classdetails where class_id = '$classID'";

    $resultClassFind = mysqli_query($con,$classFindSearch);
    $numClassResult = mysqli_num_rows($resultClassFind);
    if($numClassResult == 1){
        $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
        $update = $dbh->prepare("update classdetails set class_id = ?, class_title = ?, description = ? where class_id = $classID");
        $update->bindParam(1,$classID);
        $update->bindParam(2,$classTitleSearch);
        $update->bindParam(3,$classDescriptionSearch);
        $update->execute();   
        header("Refresh:0; administration.php");
    }
    else{
        echo "<script type='text/javascript'>alert('Update failed to process');</script>";
        header("Refresh:0; administration.php");
    }
}

if(isset($_POST['accountAdd'])){
    $accountFName = htmlspecialchars($_POST['accountFirstName'],ENT_COMPAT);
    $accountLName = htmlspecialchars($_POST['accountLastName'],ENT_COMPAT);
    $accountUserName = htmlspecialchars($_POST['accountUserName'],ENT_COMPAT);
    $accountPassword = htmlspecialchars($_POST['accountPassword'],ENT_COMPAT);
    $accountType = htmlspecialchars($_POST['accountTypeSelect'],ENT_COMPAT);
    if($accountType == "Student"){
        $accountQuery = "select * from studentdetails where student_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
        if($numAccountQueryResult >= 1){
            echo "<script type='text/javascript'>alert('There is already an existing student account with that username');</script>";
        }
        else{
            $stmt = $dbh->prepare("insert into studentdetails values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
        }
    }
    elseif($accountType == "Teacher"){
        $accountQuery = "select * from teacherdetails where teacher_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
        if($numAccountQueryResult >= 1){
            echo "<script type='text/javascript'>alert('There is already an existing teacher account with that username');</script>";
        }
        else{
            $stmt = $dbh->prepare("insert into teacherdetails values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
        }
    }
    else{
        $accountQuery = "select * from admin where admin_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
        if($numAccountQueryResult >= 1){
            echo "<script type='text/javascript'>alert('There is already an existing admin account with that username');</script>";
        }
        else{
            $stmt = $dbh->prepare("insert into admin values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
        }
    }
    header("Refresh:0; administration.php");
}
    header("Refresh:0; administration.php");
?>