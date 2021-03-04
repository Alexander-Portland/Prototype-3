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
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>
    <script src="pageInteraction.js"></script>
    </head>
    <main>
    
    <nav class="NavBar">
        <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
        <a href="teacherPage.php"><b>Home</b></a>
        <a href="logout.php"><b>Log out</b></a>
    </nav>
  

    <section id="myhelp" class="help">
        <section class="helpContent">
            <span id = "helpClose" class="close">&times;</span>
            <label class = "loginLabel"><b>Using the questions and answers page</b></label>
            <p class = "loginHelpText">To answer question from you're students, follow these steps:</p>
            <ol>
            
                <li>Press the reply button for the message you wish to reply to.</li>
                <li>Type you're response in the text box next to the title "answer"</li>
                <li>When you're satisfied with you're answer, press the send button</li>

            </ol>
            <video class = "helpVideo" controls>
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>
    </section>

    <section id="messageSuccess" class="help">
        <section class="helpContent">
            <label class = "loginLabel"><b>Using the login page</b></label>
            <button onclick ="successClose()">&times;</button>
        </section>
    </section>
    
    <section class = "centerPosClass">
        <section id = "questionReply" class="classPosts">
                    <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton expandButton" width = 40x><br>
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classID" class = "hidePost" required><br>
                        <b><p class = "displayInline">From: </p></b> <p id = "labelFrom" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Subject:</p></b> <p id = "labelSubject" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Question:</p></b> <p id = "labelQuestion" class = "displayInline"></p><br>
                        <label>Answer: </label><input type="text" name="replyInput" required><br>
                        <button name="reply">Send</button>   
                    </form>
        </section>
    </section>

    <section class = "centerPosClass">
        <section id = "classDisplay" class="classPosts">

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
                        echo '<section class = "classOutliner">';
                        echo '<button onclick ="replySend('.$QuestionID.','.$QuestionSender.','.$QuestionTitle.','.$QuestionDescription.')" class="expandButton">Reply</button>';

                        echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                        echo '<b><p class = "displayInline">From: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                        echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                        echo '<br>';
                        echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                        echo '</section>';
                        echo '<br><br>';
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
        <script>
            var selectHelp = document.getElementById("myhelp");
            var sentMessage = document.getElementById("messageSuccess");

            var btn = document.getElementById("helpBtn");
            var span = document.getElementById("helpClose");

            btn.onclick = function() {
                selectHelp.style.display = "block";
            }

            span.onclick = function() {
                selectHelp.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == selectHelp) {
                    selectHelp.style.display = "none";
                }
            }
            

        </script>
    </main>
</html>