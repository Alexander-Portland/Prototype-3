<?php

    session_start();

    //The session username and password are extracted for account checking 
    $nameCheck = $_SESSION['username'];
    $passCheck = $_SESSION['password'];

    //first connection to the database
    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    //the query below checks if there is an existing teacher account with the same username and password the user entered the page with
    $teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' && teacher_password = '$passCheck'";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    //The ID, firstname and surname of the accessignaccount are extracted
    $row = $resultTeacher->fetch_assoc();
    $ID = $row['teacher_id'];
    $Fname = $row['teacher_forname'];
    $Lname = $row['teacher_surname'];

    //$dbh acts as the connection variable allowing the user to add records to the questions database 
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    //If the account check shows the user is not accessing the page with a valid teacher account then the page takes them back to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    //The function below is only executed when the yes button of the reply section in MessengerTeacher.php is pressed
    if(isset($_POST['reply'])){
        
        //The ID of the question and the answer the user submitted is extracted
        $replyMessageID = $_POST['classID'];
        $replyAnswer = htmlspecialchars($_POST['replyInput'],ENT_COMPAT);
        echo'<script>alert("Teacher ID: '.$ID.'")</script>';
        echo'<script>alert("Message ID: '.$replyMessageID.'")</script>';
        //The query below checks if the the question they are replying to is a question that was addressed to them
        $messageFind = "select * from messages where Message_ID = '$replyMessageID' && Teacher_ID = '$ID'";
        $resultMessageFind = mysqli_query($con, $messageFind);
        $numMessageResult = mysqli_num_rows($resultMessageFind);

        

        //If the check shows that question the user is trying to reply to a question addressed to them then the reply is submitted
        if($numMessageResult == 1){
                    
                    $update = $dbh->prepare("update messages set Question_Answer = ?, Question_Answered = 1 where Message_ID = ?");
                    $update->bindParam(1,$replyAnswer);
                    $update->bindParam(2,$replyMessageID);
                    $update->execute();
                    header('location:MessengerTeacher.php');
        }
        //If the check shows the user is trying to reply to a question that is not addressed to them then the reply is aborted and the error message below is displayed
        else{
            $errorTitle = "Message answer rejected";
            $errorStatement = "You are attempting to answer a message that does not belong to you";
        }

    }
?>
<!--The code below is only displayed if the answer submission is aborted-->
<html>
    <head>
        <!--The rejection notification uses the same css and javascript page as the previous rejection notifications --> 
        <title>Answer submission</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <script src="pageInteraction.js"></script>
    </head>
    <main>
        <!--This section contains the return button, error title and description-->
        <section class = "centerPosClass">
            <section class = "helpContent">
                <!--If the user presses the button below the page will take them back to MessengerTeacher.php-->
                <form action="MessengerTeacher.php">
                        <button class= "expandButton button">Return</button>
                </form>
                <!--The error title and description are set to change value depending on the type of error the user has invoked-->
                <label class = "loginLabel"><?php echo $errorTitle ?></label>
                <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>