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
    <title>Messages Page</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>
    <script src="pageInteraction.js"></script>
    </head>
    <body>
    
    <ul>

    <li><p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p></li>

    <li><a href="teacherPage.php">Home</a></li>

    <li><a href = "logout.php">Log Out</a></li>
    
    </ul>
    <section class = "centerPosClass">
        <section id = "classDisplay" class="classPosts">
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classID" class = "hidePost" required><br>
                        <b><p class = "displayInline">From:</p></b> <p id = "labelFrom" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Subject:</p></b> <p id = "labelSubject" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Question:</p></b> <p id = "labelQuestion" class = "displayInline"></p><br>
                        <label>Answer: </label><input type="text" name="replyInput" required><br>
                        <button name="reply">Send</button>   
                    </form>
        </section>
    </section>

    <section class = "centerPosClass">
        <section id = "classDisplay" class="classPosts">
            <button onclick="hideContent('helpUpdate')" class="expandButton">?</button>
            <section id = "helpUpdate" class = "hidePost">
                <p>To reply to a question, press the "reply" button adjacent to the question you wish to answer.
                <br>When pressed an asnwer form will be displayed for you to make you're answer.
                </p>
            </section>

            <?php
            $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answered from messages where Teacher_ID = '$ID' && Question_Answered = 0";
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
                    echo '<button onclick ="replySend('.$QuestionID.','.$QuestionSender.','.$QuestionTitle.','.$QuestionDescription.')" class="expandButton">Reply</button>';

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
            if(isset($_POST['reply'])){
                $replyMessageID = $_POST['classID'];
                $replyAnswer = $_POST['replyInput'];

                $messageFind = "select Message_ID from messages where Message_ID = '$replyMessageID'";
                $resultMessageFind = mysqli_query($con, $messageFind);
                $numMessageResult = mysqli_num_rows($resultMessageFind);

                $rowMessageFind = $resultMessageFind->fetch_assoc();

                if($numMessageResult == 1){
                          
                            $update = $dbh->prepare("update messages set Question_Answer = ?, Question_Answered = 1 where Message_ID = ?");
                            $update->bindParam(1,$replyAnswer);
                            $update->bindParam(2,$replyMessageID);
                            $update->execute();
                        }

                else{
                    echo "<script type='text/javascript'>alert('The message you are replying to does not exist');</script>";
                }

            }
        ?>
    </body>
</html>