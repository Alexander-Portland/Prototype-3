<?php
    session_start();

    //The username and password of the session are extracted
    $nameCheck = $_SESSION['username'];
    $passCheck = $_SESSION['password'];

    //This is the first connection to the database
    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');
          
    //This query will check if there are any existing teacher accounts with the same username and password as session username and password
    $teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' && teacher_password = '$passCheck'";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    //The IF, first name and last name of the checked account are extracted
    $row = $resultTeacher->fetch_assoc();
    $ID = $row['teacher_id'];
    $Fname = $row['teacher_forname'];
    $Lname = $row['teacher_surname'];


    //If there is no teacher account with the same username and password as the session then the page is redirected to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    //This function is only executed when the yes button of the search student section in MessengerTeacher.php is pressed
    if(isset($_POST['historyRequest'])){
        //The inputted first and last name of the search are extracted
        $firstNameInput = htmlspecialchars($_POST['fNameSearch'],ENT_COMPAT);
        $lastNameInput = htmlspecialchars($_POST['lNameSearch'],ENT_COMPAT);

        //This query checks if there are any existing student accounts with the same first and last name as the searched student
        $studentSearch = "select student_id from studentdetails where forname = '$firstNameInput' && surname = '$lastNameInput'";
        $studentSearchQuery = mysqli_query($con, $studentSearch);
        $studentSearchQueryNum = mysqli_num_rows($studentSearchQuery);
    } 
?>
<!--This code is only displayed if the histroy request is rejected -->
<html>
    <head>
        <!--The rejection notification uses the same css and javascript page as the other rejection pages -->
        <title>Messages Histroy result</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
    </head>
    <main>
        <!--This section displays the message history of the searched student-->
        <section id = "result" class = "centerPosClass">
            <section class = "classPosts">
            <!--A second title is used to clarify which student is being searched for-->
            <p class = "teacherInteractionBoxTitle">Messages you have recieved from <?php echo $firstNameInput ?> <?php echo $lastNameInput?></p>
            <!--If the user presses the return button they will be redirected to the messengerTeacher.php page-->
            <form action="MessengerTeacher.php" method="post">
                <br><br><button class = "button expandButton">Return</button>
            </form>
            <?php
                //The system checks if there are any student's that were found in the initial first and last name search
                if($studentSearchQueryNum >= 1){
                    //If there are any students then this while loop will cycle through each student
                    while($rowSeachQueryGrab = $studentSearchQuery->fetch_assoc()): ?> 
                        <?php
                            //The Id of the student being inspected is extracted 
                            $studentPickedID = $rowSeachQueryGrab["student_id"];
                            //The ID of each displayed student acts a seperator in order to prevent the user missreading one students question from another
                            echo'<br><br><br><br><p class = "teacherInteractionBoxTitle">'.$studentPickedID.'</p>';
                        
                            //The system then searches for questions that are addressed to user and were sent by the student currently being displayed
                            $messageHistoryFind = "select * from messages where Teacher_ID = '$ID' && Student_ID = $studentPickedID && (Question_Answered = 1 || Question_Answered = 2)";
                            $resultMessageHistoryFind = mysqli_query($con, $messageHistoryFind);
                            $numMessageHistoryResult = mysqli_num_rows($resultMessageHistoryFind);
                            
                            //The system checks if there are any questions that have been sent by the student to the user
                            if($numMessageHistoryResult >= 1){
                                //If therearequestions sent by the student to the user then the while loop below cycles through each extracted question record
                                while($messageRow = $resultMessageHistoryFind->fetch_assoc()): ?> 
                                    <?php
                                        //The question title, description and answer are extracted for display
                                        $questionTitle = $messageRow['Question_Title'];
                                        $questionDescription = $messageRow['Question_Description'];
                                        $questionAnswer = $messageRow['Question_Answer'];

                                        //The section below is outputted for each extracted question with its title, descruption and answer displayed
                                        echo'<br><br><section class = "classOutliner">';
                                            echo'<p class = "displayInline"><b>Question Title: </b></p> <p class = "displayInline">'.$questionTitle.'</p><br> ';
                                            echo'<p class = "displayInline"><b>Question Description: </b></p> <p class = "displayInline">'.$questionDescription.'</p><br> ';
                                            echo'<p class = "displayInline"><b>Question Answer: </b></p> <p class = "displayInline">'.$questionAnswer.'</p><br> ';
                                        echo'</section>'
                                    ?>
                            <?php endwhile; 
                            }
                            //If the search reveals no answered questions that were addressed to the user then the message below is displayed
                            else{
                                echo'<p>You have not had any question with this student</p>';
                            }



                        ?>
                    <?php endwhile; 
                }
                //If there is no student with the specififed first and last name on the system then the message below is displayed
                else{
                    echo '<p>The student you selected does not exist on this system</p>';
                }
            ?>
            </section>
        </section>
    </main>

</html>