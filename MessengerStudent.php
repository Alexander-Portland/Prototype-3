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
        <a href="studentPage.php"><b>Home</b></a>
        <a href="logout.php"><b>Log out</b></a>
    </nav>

    <section id="myhelp" class="help">
        <section class="helpContent">
            <span id = "helpClose" class="close">&times;</span>
            <label class = "loginLabel"><b>Using the questions and answers page</b></label>
            <p class = "loginHelpText">To view you're questions and answers, take the following steps:</p>
            <ol>
            
                <li>Press "inbox" button to view you're questions that have been answered</li>
                <li>Press "sent" button to view you're questions that have not been answered"</li>
                <li>Press "add" button to begin writing a new question then take the following steps: </li>
                <ul>
                    <li>Enter the username of the teacher you wish to submit the question to in the "Recipient" box</li>
                    <li>Enter the title of the topic/subject your question is related to in the "Question Title" box</li>
                    <li>Finally, enter the you'r question in the "Question" box</li>
                <ul>

            </ol>
            <video class = "helpVideo" controls>
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>
    </section>

    <section class = "centerPosClass">
            <section class = "classPosts">
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                <button onclick="messageInbox()" class= "button">Inbox</button>
                <button onclick="messageSent()" class= "button">Sent</button>
                <button onclick="messageAdd()" class= "button">Add</button>
            </section>
    </section>
                <section id = "classAdd" class = "centerPosClass hidePost">
                    <section class = "classPosts">
                    <form method="post" enctype="multipart/form-data">
                        <p class = "teacherInteractionBoxTitle">Send New Question</p> 
                        <label><b>Recipient: </b></label>
                        <select name = "sendName">
                            <?php
                                $classPick = "select class_id from studentdetails_classdetails where student_id = '$ID'";
                                $resultClass = mysqli_query($con,$classPick);
                                while($rowClass = $resultClass->fetch_assoc()): ?>
                                    <?php
                                        $classID = $rowClass["class_id"];
                                        $teacherClassPick = "select teacher_id from teacherdetails_classdetails where class_id = '$classID'";
                                        $teacherClassExecute = mysqli_query($con,$teacherClassPick);
                                        while($rowTeacherSelect = $teacherClassExecute->fetch_assoc()): ?>
                                            <?php
                                                $teacherIDPicked = $rowTeacherSelect['teacher_id'];
                                                $teacherDetailsPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_id = '$teacherIDPicked'";
                                                $teacherExtractDetails = mysqli_query($con,$teacherDetailsPick);
                                                $teacherExtract = $teacherExtractDetails->fetch_assoc();
                                                $teacherExtractedID = $teacherExtract['teacher_id'];
                                                $teacherExtractFName = $teacherExtract['teacher_forname'];
                                                $teacherExtractLName = $teacherExtract['teacher_surname'];
                                                echo'<option value = "'.$teacherExtract['teacher_id'].'">'.$teacherExtractFName.' '.$teacherExtractLName.'</option>';
                                            ?>
                                        <?php endwhile; 
                                        ?>
                                    ?>
                                <?php endwhile; 
                            ?>
                        </select><br>
                        <label><b>Question Title: </b></label><input type="text" name="questionTitle" class = "inputButton" required><br>
                        <label><b>Question:  </b></label><textarea type="text" name="sendQuestion" class = "textInput" required></textarea><br>
                        <button name="btnSendQuestion" class = "button buttonGreen">Yes</button>
                        <button onclick="abortMessage()" class = "button buttonRed">No</button>   
                    </form>
                </section>
            </section>

    
    
            <section id = "inbox" class = "centerPosClass hidePost"> 
                <section class = "classPosts">
                    <p class = "teacherInteractionBoxTitle">Inbox</p>   
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
                                    echo '<br><section class = "classOutliner">';
                                    echo'<button class = "button expandButton">Delete</button>';
                                    echo '<b><p class = "displayInline">To: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                                    echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                                    echo '<br>';
                                    echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                                    echo '<br>';
                                    echo '<b><p class = "displayInline">Answer: </p></b> <p class = "displayInline">'.$QuestionAnswer.'</p>';
                                    echo '</section>';
                                ?> 
                        <?php endwhile;?>
                    </section>
            </section>

        
            <section id = "sent" class = "centerPosClass hidePost">
                <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Sent</p> 
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
                    $teacherID = $rowClass["Teacher_ID"];

                    $studentFnamePick = "select teacher_forname, teacher_surname from teacherdetails where teacher_id = '$teacherID'";
                    $studentQuery = mysqli_query($con,$studentFnamePick);

                    $NamesClass = $studentQuery->fetch_assoc();

                    $FnameRow = $NamesClass["teacher_forname"];
                    $LnameRow = $NamesClass["teacher_surname"];
                    $messageID = $rowClass["Message_ID"];
                    $messageTitle = $rowClass["Question_Title"];
                    $messageDescription = $rowClass["Question_Description"];
                    echo '<br><section class = "classOutliner">';
                        echo'<button class = "button expandButton">Delete</button>';
                        echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                        echo '<b><p class = "displayInline">To: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                        echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                        echo '<br>';
                        echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                    echo '</section>';
                    echo '<br><br>';
                ?>
                <?php endwhile;?>
                </section>
            </section>
        

        <?php 
                
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
            if(isset($_POST['btnSendQuestion'])){
                $sendName = $_POST['sendName'];
                $sendTitle = $_POST['questionTitle'];
                $sendQuestion = $_POST['sendQuestion'];
                $teacherQuery = "select teacher_id from teacherdetails where teacher_id = '$sendName'";
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
    <script>
            var selectHelp = document.getElementById("myhelp");
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