<?php
    session_start();


    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');

    $nameCheck = $_SESSION['username'];
                
    $teacherPick = "select * from admin where admin_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }
                
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if(isset($_POST['btnDeleteAccount'])){
        $accountID = htmlspecialchars($_POST['accountDeleteID'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountDeleteType'],ENT_COMPAT);
        if($accountType == "Student"){
            $accountQueryDelete = "delete from studentdetails where student_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
        elseif($accountType == "Teacher"){
            $accountQueryDelete = "delete from teacherdetails where teacher_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
        elseif($accountType == "Admin"){
            $accountQueryDelete = "delete from admin where admin_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
    }
    if(isset($_POST['btnUpdateAccount'])){
        $accountID = htmlspecialchars($_POST['accountUpdateID'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountUpdateType'],ENT_COMPAT);
        $updateFirstName = htmlspecialchars($_POST['Fname'],ENT_COMPAT);
        $updateLastName = htmlspecialchars($_POST['Lname'],ENT_COMPAT);
        $updateUsername = htmlspecialchars($_POST['Username'],ENT_COMPAT);
        $updatePassword = htmlspecialchars($_POST['Password'],ENT_COMPAT);

        if($accountType == "Student"){
            $update = $dbh->prepare("update studentdetails set  forname = ?, surname = ? ,student_username = ?, student_password = ? where student_id = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
        }
        elseif($accountType == "Teacher"){
            $update = $dbh->prepare("update teacherdetails set  teacher_forname = ?, teacher_surname = ? ,teacher_username = ?, teacher_password = ? where teacher_id = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
        }
        elseif($accountType == "Admin"){
            $update = $dbh->prepare("update admin set  forename = ?, surname = ? ,admin_username = ?, admin_password = ? where admin_ID = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
        }

    }
    if(isset($_POST['btnAddClassAccount'])){
        $accountID = htmlspecialchars($_POST['accountAddClassID'],ENT_COMPAT);
        $classPick = htmlspecialchars($_POST['lessonSelect'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountAddType'],ENT_COMPAT);
        if($accountType == "Student"){
            $checkClass = "select * from studentdetails_classdetails where student_id = $accountID && class_id = $classPick";
            $checkClassExecute = mysqli_query($con,$checkClass);
            $numCheckClassExecute = mysqli_num_rows($checkClassExecute);
            if($numCheckClassExecute >= 1){
                echo "<script type='text/javascript'>alert('This account has already been assigned this class');</script>";
            }
            else{
                $stmt = $dbh->prepare("insert into studentdetails_classdetails values('',?,?)");
                $stmt->bindParam(1,$accountID);
                $stmt->bindParam(2,$classPick);
                $stmt->execute();
            }
        }
        elseif($accountType == "Teacher"){
            $checkClass = "select * from teacherdetails_classdetails where teacher_id = $accountID && class_id = $classPick";
            $checkClassExecute = mysqli_query($con,$checkClass);
            $numCheckClassExecute = mysqli_num_rows($checkClassExecute);
            if($numCheckClassExecute >= 1){
                echo "<script type='text/javascript'>alert('This account has already been assigned this class');</script>";
            }
            else{
                $stmt = $dbh->prepare("insert into teacherdetails_classdetails values('',?,?)");
                $stmt->bindParam(1,$accountID);
                $stmt->bindParam(2,$classPick);
                $stmt->execute();
            }
        }
    }
    header("Refresh:0; administration.php");
    
    
?>