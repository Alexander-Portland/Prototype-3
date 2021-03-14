<?php

    session_start();

    //The username and password of the session are extracted 
    $nameCheck = $_SESSION['username'];
    $passCheck = $_SESSION['password'];

    //First connection to the database is established
    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    //The query checks if the session username and password checkout with an existing student account
    $studentPick = "select student_id, forname, surname from studentdetails where student_username = '$nameCheck' && student_password = '$passCheck' ";


    $resultStudent = mysqli_query($con,$studentPick);
    $numStudent = mysqli_num_rows($resultStudent);

    //The ID, first name and last name are extracted
    $row = $resultStudent->fetch_assoc();
    $ID = $row['student_id'];
    $Fname = $row['forname'];
    $Lname = $row['surname'];

    //$dhb acts as a connection variable that will allow the page to submit new questions from MessengerStudent.php
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    //If the session username and password do not check out with an existing account then the user is redirected to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    //This function is only executed when the yes button of the send question function on MessengerStudent.php is pressed
    if(isset($_POST['btnSendQuestion'])){
        //The recipient input, question title and question are extracted from MessengerStudent.php
        $sendName = $_POST['sendName'];
        $sendTitle = htmlspecialchars($_POST['questionTitle'],ENT_COMPAT);
        $sendQuestion = htmlspecialchars($_POST['sendQuestion'],ENT_COMPAT);

        //The falling query checks if the selected teacher exists on the database
        $teacherQuery = "select teacher_id from teacherdetails where teacher_id = '$sendName'";
        $resultTeacher = mysqli_query($con,$teacherQuery);
        $numResultTeacher = mysqli_num_rows($resultTeacher);

        //If the selected teacher does not exist on the system then the send question is aborted and the below error message is displayed
        if($numResultTeacher == 0){
            $errorTitle = "Question rejected";
            $errorStatement = "The teacher you selected does not exist on the system";
        }
        //If the selected teacher does exist on the system then the second phase of validation is executed
        else{
            //The teacher ID is extracted for further checks
            $teacherSelect = $resultTeacher->fetch_assoc();
            $teacherID = $teacherSelect['teacher_id'];

            //The following query aquires what classes the selected teacher has been assigned to
            $teacherClassExtract = "select class_id from teacherdetails_classdetails where teacher_id = '$teacherID'";
            $resultClassExtract = mysqli_query($con,$teacherClassExtract);
            $numResultClassExtract = mysqli_num_rows($resultClassExtract);
            
            //The final validation check uses the below variable to idnicate if the sending student has the selected a teacher they are assigned to
            $isAssigned = "0";

            //The while loop goes through all of the classes the selected teacher has been assigned to
            while($rowClassExtract = $resultClassExtract->fetch_assoc()): ?>
                <?php
                    $class_ID = $rowClassExtract['class_id'];

                    //The query below checks if one of the classes the teacher is assigned to is a class the student is assigned to 
                    $studentClassCheck = "select student_id from studentdetails_classdetails where student_id = '$ID' && class_id = '$class_ID'";
                    $resultStudentClassCheck = mysqli_query($con,$studentClassCheck);
                    $numResultStudentClassCheck = mysqli_num_rows($resultStudentClassCheck);

                    //If a student is assigned to the same class as the selected teacher then the check variable is set to conifrm
                    if($numResultStudentClassCheck >= 1){
                        $isAssigned = "1";
                    }
                ?>
            <?php endwhile; 

            //if the check confirms both the student and teacher are in the same class then the message submission is executed
            if($isAssigned == "1"){
                $stmt = $dbh->prepare("insert into messages values('',?,?,?,?,0,'')");
                $stmt->bindParam(1,$ID);
                $stmt->bindParam(2,$teacherID);
                $stmt->bindParam(3,$sendTitle);
                $stmt->bindParam(4,$sendQuestion);
                $stmt->execute();
                header('location:MessengerStudent.php');
            }
            //if the check does not confirm both the student and the teacher are in the same class the message submission is aborted and the error message below is displayed
            else{
                $errorTitle = "Question rejected";
                $errorStatement = "You are attempting to send a question to a teacher you have not been assigned to";
            }   
        }
    }

    //The following function is only executed when the yes button of the delete section from MessengerStudent.php is pressed
    if(isset($_POST['btnDelete'])){
        //The ID of the question the user selected is extracted
        $messageID = $_POST['messageDeleteID'];
        
        //The following query checks if the user is the correct owner of the question they are trying to delete 
        $messageFind = "select Message_ID from messages where Message_ID = '$messageID' && Student_ID = '$ID'";
        $resultClassFind = mysqli_query($con,$messageFind);
        $numDeleteResult = mysqli_num_rows($resultClassFind);

        //If the check shows the user is the correct owner of the question then the deletion process is executed
        if($numDeleteResult == 1){
            $postRemove = "update messages set Question_Answered = 2 where Message_ID = $messageID";
            $postRemoveQuery = mysqli_query($con,$postRemove);
            header('location:MessengerStudent.php');
        }
        //If the check shows that the user is not the correct owner of the question then the deletion is aborted and the error message below is displayed 
        else{
            $errorTitle = "Question delete rejected";
            $errorStatement = "You are attempting to delete a message that does not belong to you";
        }
    }
    
?>

<!--The following code is only displayed if the user attempts to add or remove a question is rejected-->
<html>
    <head> 
        <!--The rejection page uses the same css and javascript page as the previous rejection pages -->
        <title>Message submission</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <script src="pageInteraction.js"></script>
    </head>
    <main>
        <!--Both the return button, error title and explanation are kept within the same section-->
        <section class = "centerPosClass">
            <section class = "helpContent">
                <!--If the ueser presses the return button they will be returned to their question homepage-->
                <form action="MessengerStudent.php">
                        <button class= "expandButton button">Return</button>
                </form>
                <!--The error title and explanation are set to display different messages depending on the type of rejection -->
                <label class = "loginLabel"><?php echo $errorTitle ?></label>
                <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>