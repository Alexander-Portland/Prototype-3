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
    <script src="pageInteraction.js"></script>
    </head>
    <body>
    <ul>

    <li><p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p></li>

    <li><a href="studentPage.php">Home</a></li>

    <li><a href = "logout.php">Log Out</a></li>
    
    </ul>

    <section class = "centerPosClass">
            <section class = "classPosts">
                <button onclick="hideContent('inbox') ,minimise('sent'), minimise('classAdd')">Inbox</button>
                <button onclick="hideContent('sent') ,minimise('inbox'), minimise('classAdd')">Sent</button>
                <button onclick="hideContent('classAdd') ,minimise('inbox'),minimise('sent') ">Add</button>
                <button onclick="hideContent('')">?</button>
                <section id = "classAdd" class = "hidePost">
                    <form method="post" enctype="multipart/form-data">
                        <label>Recipient: </label><input type="text" name="sendName" required><br>
                        <label>Question Title: </label><input type="text" name="questionTitle" require><br>
                        <label>Question:  </label><input type="text" name="sendQuestion" required><br>
                        <button name="btnSendQuestion">Send</button>   
                    </form>
                </section>
        <section>
            <section id = "inbox" class = "hidePost">
                <p><b>Inbox</b></p>
            <?php
            $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answer,Question_Answered from messages where Student_ID = '$ID' && Question_Answered = 1";
            $resultClass = mysqli_query($con,$classPick);
            $numClass = mysqli_num_rows($resultClass);


            while($rowClass = $resultClass->fetch_assoc()): ?> 
                <?php
                    $QuestionID = rand();
                    $QuestionSender = rand();
                    $QuestionTitle = rand();
                    $QuestionDescription = rand();
                    $teacherID = $rowClass["Teacher_ID"];

                    $studentFnamePick = "select teacher_forname, teacher_surname from teacherdetails where teacher_id = '$teacherID'";
                    $studentQuery = mysqli_query($con,$studentFnamePick);

                    $NamesClass = $studentQuery->fetch_assoc();

                    $FnameRow = $NamesClass["teacher_forname"];
                    $LnameRow = $NamesClass["teacher_surname"];
                    $messageID = $rowClass["Message_ID"];
                    $messageTitle = $rowClass["Question_Title"];
                    $messageDescription = $rowClass["Question_Description"];
                    $QuestionAnswer = $rowClass["Question_Answer"];
                    echo '<b><p class = "displayInline">To: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                    echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                    echo '<br>';
                    echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                    echo '<br>';
                    echo '<b><p>Answer</p></b> <p>'.$QuestionAnswer.'</p>';
                    echo '<br>';
                    echo '<br>';
                ?>
                <?php endwhile; ?>
            </section>
        </section>

        <section>
            <section id = "sent" class = "hidePost">
            
                <p><b>Sent</b></p>
                <?php
                $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answered from messages where Student_ID = '$ID' && Question_Answered = 0";
                $resultClass = mysqli_query($con,$classPick);
                $numClass = mysqli_num_rows($resultClass);


                while($rowClass = $resultClass->fetch_assoc()): ?> 
                <?php
                    $QuestionID = rand();
                    $QuestionSender = rand();
                    $QuestionTitle = rand();
                    $QuestionDescription = rand();
                    $studentID = $rowClass["Student_ID"];

                    $studentFnamePick = "select forname, surname from studentdetails where student_id = '$studentID'";
                    $studentQuery = mysqli_query($con,$studentFnamePick);

                    $NamesClass = $studentQuery->fetch_assoc();

                    $FnameRow = $NamesClass["forname"];
                    $LnameRow = $NamesClass["surname"];
                    $messageID = $rowClass["Message_ID"];
                    $messageTitle = $rowClass["Question_Title"];
                    $messageDescription = $rowClass["Question_Description"];
                    echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                    echo '<b><p class = "displayInline">From: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                    echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                    echo '<br>';
                    echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                    echo '<br>';
                    echo '<br>';
                ?>
                <?php endwhile; ?>

            </section>
        </section>

        <?php 
                
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
            if(isset($_POST['btnSendQuestion'])){
                $sendName = $_POST['sendName'];
                $sendTitle = $_POST['questionTitle'];
                $sendQuestion = $_POST['sendQuestion'];
                $teacherQuery = "select teacher_id from teacherdetails where teacher_username = '$sendName'";
                $resultTeacher = mysqli_query($con,$teacherQuery);
                $numResultTeacher = mysqli_num_rows($resultTeacher);

                if($numResultTeacher == 0){
                    echo "<script type='text/javascript'>alert('The teacher you selected does not exist');</script>";
                }
                else{
                    echo "<script type='text/javascript'>alert('Success');</script>";
                    $teacherSelect = $resultTeacher->fetch_assoc();
                    $teacherID = $teacherSelect['teacher_id'];
                    $stmt = $dbh->prepare("insert into messages values('',?,?,?,?,0,'')");
                    $stmt->bindParam(1,$ID);
                    $stmt->bindParam(2,$teacherID);
                    $stmt->bindParam(3,$sendTitle);
                    $stmt->bindParam(4,$sendQuestion);
                    $stmt->execute();
                }
            }
        ?>
        </section>
    </section>
    </body>
</html>