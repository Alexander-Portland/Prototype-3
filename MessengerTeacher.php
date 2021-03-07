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
                </video>
            </section>
        </section>

        <section class = "centerPosClass">
                <section class = "classPosts">
                    <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                    <button onclick="teacherMessageInbox()" class= "button">Open Inbox</button>
                    <button onclick="teacherMessageHistory()" class= "button">View Question History</button>
                </section>
        </section>

        <section id = "questionReply" class = "centerPosClass hidepost">
            <section class="classPosts">
                <section id = "replyContent">
                    <p class = "teacherInteractionBoxTitle">Reply</p> 
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classID" class = "hidePost" required><br>
                        <b><p class = "displayInline">From: </p></b> <p id = "labelFrom" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Subject:</p></b> <p id = "labelSubject" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Question:</p></b> <p id = "labelQuestion" class = "displayInline"></p><br>
                        <label>Answer: </label><textarea type="text" name="replyInput" class = "textInput" required></textarea><br>
                        <p><b>Are you sure you want to send this answer?</b></p>
                        <button name="reply" class = "button buttonGreen">Yes</button>
                        <button onclick="abortTeacherMessage()" class = "button buttonRed">No</button>    
                    </form>
                </section>
            </section>
        </section>

        <section id = "messageHistory" class = "centerPosClass hidepost">
            <section class = "classPosts">
                <form action="messageHistory.php" method="post">
                    <p id = "addClassTitle" class = "teacherInteractionBoxTitle">Search Student</p>
                    <label><b>First Name</b></label><input type="text" name="fNameSearch" class = "inputButton" required>
                    <label><b>Last Name</b></label><input type="text" name="lNameSearch" class = "inputButton" required>
                    <p><b>Are you sure you wish to search for this student?</b></p>
                    <button name="historyRequest" class = "button buttonGreen">Yes</button> 
                    <button onclick="abort();" class = "button buttonRed">No</button>
                </form>
            </section>
        </section>

        <section class = "centerPosClass">
            <section id = "classDisplay" class="classPosts hidepost"> 
                <p class = "teacherInteractionBoxTitle">Inbox</p>  
                <?php
                $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answered from messages where Teacher_ID = '$ID' && Question_Answered = 0";
                $resultClass = mysqli_query($con,$classPick);
                $numClass = mysqli_num_rows($resultClass);
                if($numClass >= 1){
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
                            echo '<br><section class = "classOutliner">';
                            echo '<button onclick ="replySend('.$QuestionID.','.$QuestionSender.','.$QuestionTitle.','.$QuestionDescription.')" class="expandButton button">Reply</button>';

                            echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                            echo '<b><p class = "displayInline">From: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                            echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                            echo '<br>';
                            echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                            echo '</section>';
                            echo '<br><br>';
                        ?>
                    <?php endwhile; 
                    }
                    else{
                        echo '<p>You have zero questions to answer.</p>';
                    }
                    ?>
                
            </section>
        </section>

        <?php
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
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