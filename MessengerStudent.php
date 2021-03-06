<?php

session_start();
//the session username and password are pulled from the users homepage
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//first connection to the database
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//the first query checks if the session username and password checkout with an existing student account
$studentPick = "select student_id, forname, surname from studentdetails where student_username = '$nameCheck' && student_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

//the account ID, forname  and surname are extracted from the results of the first query
$row = $resultStudent->fetch_assoc();
$ID = $row['student_id'];
$Fname = $row['forname'];
$Lname = $row['surname'];

//If no account checks out with the session username or password the user is taken back to the index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>

<html>
    <!--The student's messenger page is linked to a single css and javascript page -->
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

    <!--The navigation bar gives the user access to their hompage and the logout sequence -->
    <nav class="NavBar">
        <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
        <a href="studentPage.php"><b>Home</b></a>
        <a href="logout.php"><b>Log out</b></a>
    </nav>

    <!--The help page is made visible when the user interacts with the help button, which is an image -->
    <section id="myhelp" class="help">
        <section class="helpContent">
            <!--The help page's tutorial contains a text and video tutorial -->
            <span id = "helpClose" class="close">&times;</span>
            <label class = "loginLabel"><b>Using the questions and answers page</b></label>
            <p class = "loginHelpText">To view your questions and answers, take the following steps:</p>
            <!--This is the text segement of the tutorial -->
            <ol>
            
                <li>Press "inbox" button to view your questions that have been answered</li>
                <li>Press "sent" button to view your questions that have not been answered</li>
                <li>Press "send new quesion" button to begin writing a new question then take the following steps: </li>
                <ul>
                    <li>select the teacher you wish to send the question to in the recipient input</li>
                    <li>Enter the title of the topic/subject your question is related to in the "Question Title" box</li>
                    <li>Enter the your question in the "Question" box</li>
                    <li>Finally press the yes button to send the question</li>
                <ul>

            </ol>
            <!--This is the video segement of the tutorial -->
            <video class = "helpVideo" controls>
                <source src="vid/StudentQuestionPageHelp.mp4" type="video/mp4">
            </video>
        </section>
    </section>

    <!--This section displays a second navbar with a help, inbox, sent and add button -->
    <section class = "centerPosClass">
            <section class = "classPosts">
                <!--Inbox button will display answered question, sent will display unanswered question and add will open the new message send section -->
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                <button onclick="messageInbox()" class= "button">Inbox</button>
                <button onclick="messageSent()" class= "button">Sent</button>
                <button onclick="messageAdd()" class= "button">Send New Question</button>
            </section>
    </section>
        <!--This section contains the application that allows the user to address and send new questions -->
        <section id = "classAdd" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <!--The form contains five inputs: the recipient, question title, question, yes and no button -->
                <form action="MessengerStudentSubmissions.php" method="post">
                    <p class = "teacherInteractionBoxTitle">Send New Question</p> 
                    <label><b>Recipient: </b></label>
                    <!--The drop down menu allows the user to address the question -->
                    <select name = "sendName">
                        <?php
                            //This query grabs the class ID of every class the student is assigned to
                            $classPick = "select class_id from studentdetails_classdetails where student_id = '$ID'";
                            $resultClass = mysqli_query($con,$classPick);

                            //The following while loop searches for each teacher assigned to each class that the student has been assigned to and appended to the recipient input
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
                            <?php endwhile; 
                        ?>
                    </select><br>
                    <!--The user will be able to input the title of the question and the actual question in two seperate inputs -->
                    <label><b>Question Title: </b></label><input type="text" name="questionTitle" class = "inputButton" required><br>
                    <label><b>Question:  </b></label><textarea type="text" name="sendQuestion" class = "textInput" required></textarea><br>
                    <p><b>Are you sure you want to send this question?</b></p>
                    <!--If the yes button is pressed the send message function in MessengerStudentSubmissions.php is executed -->
                    <button name="btnSendQuestion" class = "button buttonGreen">Yes</button>
                    <!--If the no button is pressed then the send message is aborted and the section is closed -->
                    <button onclick="abortMessage()" class = "button buttonRed" type = "button">No</button>   
                </form>
            </section>
        </section>

        <!--The section below is used for deleting existing messages -->
        <section id = "deleteMessage" class = "centerPosClass hidePost">
            <section class = "classPosts">
                 <p class = "teacherInteractionBoxTitle">Delete Message</p> 
                <!--This section contains two inputs, the yes and no button -->
                <form action="MessengerStudentSubmissions.php" method="post">
                    <input type="text" name ="messageDeleteID" class = "hidepost"><br>
                    <!--The section will display who the question is sent to, the subject of the question and the question itself -->
                    <b><p class = "displayInline">To: </p></b><p id = "deleteMessageTo" class = "displayInline"></p><br> 
                    <b><p class = "displayInline">Subject: </p></b><p id = "deleteMessageSubject" class = "displayInline"></p><br>
                    <b><p class = "displayInline">Question: </p></b><p id = "deleteMessageQuestion" class = "displayInline"></p><br>
                    <b><p>Are you sure you want to delete this?</p></b>
                    <!--If the yes button is pressed is pressed then the deletion function in MessengerStudentSubmissions.php will be executed -->
                    <button name="btnDelete" class = "button buttonGreen">Yes</button>
                    <!--If the no button is pressed the deletion is aborted and the section is hidden -->
                    <button onclick="abortDeleteMessage()" class = "button buttonRed" type = "button">No</button>
                </form>
            </section>
        </section>
    
        <!--This section contains the inbox that displays the students unanswered questions-->
        <section id = "inbox" class = "centerPosClass hidePost"> 
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Inbox</p>   
                <?php
                //The first query for this section grabs the questions that were addressed by the student that have also been answered 
                $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answer,Question_Answered from messages where Student_ID = '$ID' && Question_Answered = 1";
                $resultClass = mysqli_query($con,$classPick);
                $numClass = mysqli_num_rows($resultClass);
                //The system then checks if the user has any answered questions to view
                if($numClass >= 1){
                        //The system then loops through each answered question to display
                        while($rowClass = $resultClass->fetch_assoc()): ?> 
                            <?php
                                //Randomly generated IDs are needed to identify the displayed question content
                                $QuestionID = rand();
                                $QuestionSender = rand();
                                $QuestionTitle = rand();
                                $QuestionDescription = rand();
                                $teacherID = $rowClass["Teacher_ID"];

                                //This query is used to extract the details of the teacher the extracted question was addressed to 
                                $studentFnamePick = "select teacher_forname, teacher_surname from teacherdetails where teacher_id = '$teacherID'";
                                $studentQuery = mysqli_query($con,$studentFnamePick);
                                $NamesClass = $studentQuery->fetch_assoc();

                                //All of the details of the answered question are then extracted
                                $FnameRow = $NamesClass["teacher_forname"];
                                $LnameRow = $NamesClass["teacher_surname"];
                                $messageID = $rowClass["Message_ID"];
                                $messageTitle = $rowClass["Question_Title"];
                                $messageDescription = $rowClass["Question_Description"];
                                $QuestionAnswer = $rowClass["Question_Answer"];

                                //Extracted questions details are then displayed in a seperated section
                                echo '<br><section class = "classOutliner">';
                                echo'<button class = "button expandButton" onclick = "messageDeleteSend('.$QuestionID.','.$QuestionSender.','.$QuestionTitle.','.$QuestionDescription.')">Delete</button>';
                                echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                                echo '<b><p class = "displayInline">To: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                                echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                                echo '<br>';
                                echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                                echo '<br>';
                                echo '<b><p class = "displayInline">Answer: </p></b> <p class = "displayInline">'.$QuestionAnswer.'</p>';
                                echo '</section>';
                            ?>
                                
                    <?php endwhile;
                    }
                    //If there are no answered question for the user then the following notification is displayed in the inbox section
                    else{
                        echo '<p>Inbox is empty, you have either not submitted a question or none of the questions have been answered</p>';
                    }
                    ?>
                </section>
        </section>

        <!--This section will display all of the unanswered question that the user has sent-->
        <section id = "sent" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Sent</p> 
                <?php

                //The system will search for questions whos student ID matches the user and that has not been answered
                $classPick = "select Message_ID, Student_ID, Teacher_ID, Question_Title, Question_Description, Question_Answered from messages where Student_ID = '$ID' && Question_Answered = 0";
                $resultClass = mysqli_query($con,$classPick);
                $numClass = mysqli_num_rows($resultClass);
                //The system then checks if there are any unanswered questions sent by the user
                if($numClass >= 1){
                    //If there are any unanswered questions sent by the user the system loops through each extracted record
                    while($rowClass = $resultClass->fetch_assoc()): ?> 
                    <?php
                        //Randomly generated IDs are needed for each of the display questions content
                        $QuestionID = rand();
                        $QuestionSender = rand();
                        $QuestionTitle = rand();
                        $QuestionDescription = rand();

                        //the teacher ID of the question is extracted for the next search
                        $teacherID = $rowClass["Teacher_ID"];

                        //The system locates the details of the teacher the question is addressed to by using the teacher ID extracted from the question record
                        $studentFnamePick = "select teacher_forname, teacher_surname from teacherdetails where teacher_id = '$teacherID'";
                        $studentQuery = mysqli_query($con,$studentFnamePick);

                        $NamesClass = $studentQuery->fetch_assoc();

                        //The details of the question and the teacher its addressed to is extracted from both previous search queries
                        $FnameRow = $NamesClass["teacher_forname"];
                        $LnameRow = $NamesClass["teacher_surname"];
                        $messageID = $rowClass["Message_ID"];
                        $messageTitle = $rowClass["Question_Title"];
                        $messageDescription = $rowClass["Question_Description"];

                        //This section displays a signle unanswered question with the previously extracted information of the question displayed
                        echo '<br><section class = "classOutliner">';
                            //The button bellow allows the user to display the question removal application
                            echo'<button class = "button expandButton" onclick = "messageDeleteSend('.$QuestionID.','.$QuestionSender.','.$QuestionTitle.','.$QuestionDescription.')">Delete</button>';
                            echo '<b><p class = "hidePost">Message ID: </p></b> <p id = '.$QuestionID.' class = "hidePost">'.$messageID.'</p>';
                            echo '<b><p class = "displayInline">To: </p></b> <p id = '.$QuestionSender.' class = "displayInline">'.$FnameRow.' '.$LnameRow.'</p>   ';
                            echo '<b><p class = "displayInline">Subject: </p></b> <p id = '.$QuestionTitle.' class = "displayInline">'.$messageTitle.'</p>';
                            echo '<br>';
                            echo '<b><p class = "displayInline">Question: </p></b> <p id = '.$QuestionDescription.' class = "displayInline">'.$messageDescription.'</p>';
                        echo '</section>';
                        echo '<br><br>';
                    ?>
                    <?php endwhile;
                }
                //If there are no unanswered questions to display then the message below is displayed inside of the sent section
                else{
                    echo '<p>You have not submitted any questions</p>';
                }
                ?>
            </section>
        </section>

        <!--The scripts below are used to hide and display the help box-->
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