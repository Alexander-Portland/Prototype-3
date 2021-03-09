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

    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    if(isset($_POST['btnSendQuestion'])){
        $sendName = $_POST['sendName'];
        $sendTitle = htmlspecialchars($_POST['questionTitle'],ENT_COMPAT);
        $sendQuestion = htmlspecialchars($_POST['sendQuestion'],ENT_COMPAT);
        $teacherQuery = "select teacher_id from teacherdetails where teacher_id = '$sendName'";
        $resultTeacher = mysqli_query($con,$teacherQuery);
        $numResultTeacher = mysqli_num_rows($resultTeacher);
        echo "<script type='text/javascript'>alert($numResultTeacher);</script>";
        if($numResultTeacher == 0){
            echo "<script type='text/javascript'>alert('The teacher you selected does not exist');</script>";
        }
        else{
            $teacherSelect = $resultTeacher->fetch_assoc();
            $teacherID = $teacherSelect['teacher_id'];
            $stmt = $dbh->prepare("insert into messages values('',?,?,?,?,0,'')");
            $stmt->bindParam(1,$ID);
            $stmt->bindParam(2,$teacherID);
            $stmt->bindParam(3,$sendTitle);
            $stmt->bindParam(4,$sendQuestion);
            $stmt->execute();
            header('location:MessengerStudent.php');
        }
    }

    if(isset($_POST['btnDelete'])){
        $messageID = $_POST['messageDeleteID'];
        $messageFind = "select Message_ID from messages where Message_ID = '$messageID'";
        $resultClassFind = mysqli_query($con,$messageFind);
        $numDeleteResult = mysqli_num_rows($resultClassFind);
        if($numDeleteResult == 1){
            $postRemove = "update messages set Question_Answered = 2 where Message_ID = $messageID";
            $postRemoveQuery = mysqli_query($con,$postRemove);
            header('location:MessengerStudent.php');
        }
        else{
            echo "<script type='text/javascript'>alert('Deletion failed to process');</script>";
            header('location:MessengerStudent.php');
        }
    }
    header('location:MessengerStudent.php')
?>