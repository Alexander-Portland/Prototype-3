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


    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    if(isset($_POST['reply'])){
        $replyMessageID = $_POST['classID'];
        $replyAnswer = htmlspecialchars($_POST['replyInput'],ENT_COMPAT);

        $messageFind = "select Message_ID from messages where Message_ID = '$replyMessageID'";
        $resultMessageFind = mysqli_query($con, $messageFind);
        $numMessageResult = mysqli_num_rows($resultMessageFind);

        $rowMessageFind = $resultMessageFind->fetch_assoc();

        if($numMessageResult == 1){
                    $update = $dbh->prepare("update messages set Question_Answer = ?, Question_Answered = 1 where Message_ID = ?");
                    $update->bindParam(1,$replyAnswer);
                    $update->bindParam(2,$replyMessageID);
                    $update->execute();
                    header('location:MessengerTeacher.php');
        }

        else{
            echo "<script type='text/javascript'>alert('The message you are replying to does not exist');</script>";
            header('location:MessengerTeacher.php');
        }

    }
?>