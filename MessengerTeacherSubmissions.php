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

        $messageFind = "select Message_ID from messages where Message_ID = '$replyMessageID' && Teacher_ID = '$ID";
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
            $errorTitle = "Message answer rejected";
            $errorStatement = "You are attempting to answer a message that does not belong to you";
        }

    }
?>

<html>
    <head> 
        <title>Answer submission</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <script src="pageInteraction.js"></script>
    </head>
    <main>
        <section class = "centerPosClass">
            <section class = "helpContent">
                <form action="MessengerStudent.php">
                        <button class= "expandButton button">Return</button>
                </form>
                <label class = "loginLabel"><?php echo $errorTitle ?></label>
                <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>