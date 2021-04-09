<?php

session_start();

//the session username and password are extracted for checks
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//First conncection to the database is established
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//The following query checks if the user has the username and password of an existing teacher
$teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' && teacher_password = '$passCheck' ";

$resultTeacher = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultTeacher);

//The ID, first name and last name of the account is extracted
$row = $resultTeacher->fetch_assoc();
$ID = $row['teacher_id'];
$Fname = $row['teacher_forname'];
$Lname = $row['teacher_surname'];

//If the check shows that there is not an existing teacher account with the same username and password, the user is taken back to the index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>


<html>
    <head>
    <!--This page uses a single css and javascript page -->
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
        <!--The navigation bar gives the user access to their hompage and the logout sequence-->
        <nav class="NavBar">
            <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
            <a href="teacherPage.php"><b>Home</b></a>
            <a href="logout.php"><b>Log out</b></a>
        </nav>
    
        <!--The section below contains the help page that is displayed and hidden when the user presses the help button-->
        <section id="myhelp" class="help">
            <section class="helpContent">
                <!--The help section is closes when the user presses the button below-->
                <span id = "helpClose" class="close">&times;</span>
                <label class = "loginLabel"><b>Using the questions and answers page</b></label>
                <p class = "loginHelpText">To answer question from you're students, follow these steps:</p>
                <!--This is the text part of the tutorial-->
                <ol>
                
                    <li>Press the reply button for the message you wish to reply to.</li>
                    <li>Type you're response in the text box next to the title "answer"</li>
                    <li>When you're satisfied with you're answer, press the send button</li>
                </ol>
                <!--This is the video part of the tutorial -->
                <video class = "helpVideo" controls>
                    <source src="vid/TeacherQuestionPageHelp.mp4" type="video/mp4">
                </video>
            </section>
        </section>

        <!--This section gives the user access to the help button, inbox and view history buttons -->
        <section class = "centerPosClass">
                <section class = "classPosts">
                    <!--When pressed the help box will be dispayed -->
                    <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                    <!--When pressed either the inbox or view history sections are displayed -->
                    <button onclick="teacherMessageInbox()" class= "button">Open Inbox</button>
                    <button onclick="teacherMessageHistory()" class= "button">View History</button>
                </section>
        </section>

        <!--This section allows the user to reply to questions addressed to them -->
        <section id = "questionReply" class = "centerPosClass hidepost">
            <section class="classPosts">
                <section id = "replyContent">
                    <p class = "teacherInteractionBoxTitle">Reply</p>
                    <!--The form below displays the sending student's name, question subject, question and a text input for the teachers answer --> 
                    <form action="MessengerTeacherSubmissions.php" method="post">
                        <input type="text" name ="classID" class = "hidePost" required><br>
                        <b><p class = "displayInline">From: </p></b> <p id = "labelFrom" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Subject:</p></b> <p id = "labelSubject" class = "displayInline"></p><br>
                        <b><p class = "displayInline">Question:</p></b> <p id = "labelQuestion" class = "displayInline"></p><br>
                        <label>Answer: </label><textarea type="text" name="replyInput" class = "textInput" required></textarea><br>
                        <p><b>Are you sure you want to send this answer?</b></p>
                        <!--If the user presses this button then the answer will be submitted-->
                        <button name="reply" class = "button buttonGreen">Yes</button>
                        <!--If the user presses this button then the answer will be aborted and the section will be hidden-->
                        <button onclick="abortTeacherMessage()" class = "button buttonRed" type = "button">No</button>    
                    </form>
                </section>
            </section>
        </section>

        <!--This section is for giving the user the chance to display the question history of a searched student -->
        <section id = "messageHistory" class = "centerPosClass hidepost">
            <section class = "classPosts">
                <!--The form below contains a text input for entering the students first and last name as well as a yes and no button-->
                <form action="messageHistory.php" method="post">
                    <p id = "addClassTitle" class = "teacherInteractionBoxTitle">Search Student</p>
                    <label><b>First Name</b></label><input type="text" name="fNameSearch" class = "inputButton" required>
                    <label><b>Last Name</b></label><input type="text" name="lNameSearch" class = "inputButton" required>
                    <p><b>Are you sure you wish to search for this student?</b></p>
                    <!--If the user presses this button then the search request is submitted-->
                    <button name="historyRequest" class = "button buttonGreen">Yes</button>
                    <!--If the user presses this button then the search request is aborted and the section is hidden -->
                    <button onclick="abortMessageSearch();" class = "button buttonRed" type = "button">No</button>
                </form>
            </section>
        </section>

        <!--This section is used for displaying the users inbox: containing all the unanswered questions addressed to them-->
        <section class = "centerPosClass">
            <section id = "classDisplay" class="classPosts hidepost"> 
                <p class = "teacherInteractionBoxTitle">Inbox</p>  
                <?php
                //The query below searches for all of the messages who are addressed to the user
                $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answered from messages where Teacher_ID = '$ID' && Question_Answered = 0";
                $resultClass = mysqli_query($con,$classPick);
                $numClass = mysqli_num_rows($resultClass);
                //The system then checks if any records were extracted from the classpick query
                if($numClass >= 1){
                    //If the check shows that records were retracted then the while loop below cycles throguh each of those extracted records
                    while($rowClass = $resultClass->fetch_assoc()): ?> 
                        <?php
                            //Randomly generated IDs are needed to identify the content parts of the inbox
                            $QuestionID = rand();
                            $QuestionSender = rand();
                            $QuestionTitle = rand();
                            $QuestionDescription = rand();

                            //Student ID is extracted for extracting student details
                            $studentID = $rowClass["Student_ID"];

                            //The query below extracts the forename and surname of the student who sent the question
                            $studentFnamePick = "select forname, surname from studentdetails where student_id = '$studentID'";
                            $studentQuery = mysqli_query($con,$studentFnamePick);

                            $NamesClass = $studentQuery->fetch_assoc();

                            //The details of both the submitted question and student who sent it are extracted for display
                            $FnameRow = $NamesClass["forname"];
                            $LnameRow = $NamesClass["surname"];
                            $messageID = $rowClass["Message_ID"];
                            $messageTitle = $rowClass["Question_Title"];
                            $messageDescription = $rowClass["Question_Description"];

                            //For each question record extracted a seperate section containing the details of the question and the sender as well as a reply button
                            echo '<br><section class = "classOutliner">';
                            //When the reply button is pressed the reply section will be opened with the details below inserted into the input fields
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
                    //If the user has no questions in their inbox then the message below is displayed
                    else{
                        echo '<p>You have zero questions to answer.</p>';
                    }
                    ?>
                
            </section>
        </section>
            <!--The script below is responsible for displaying and hiding the help box-->
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