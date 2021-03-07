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

    if(isset($_POST['historyRequest'])){

        $firstNameInput = htmlspecialchars($_POST['fNameSearch'],ENT_COMPAT);
        $lastNameInput = htmlspecialchars($_POST['lNameSearch'],ENT_COMPAT);
        $studentSearch = "select student_id from studentdetails where forname = '$firstNameInput' && surname = '$lastNameInput'";
        $studentSearchQuery = mysqli_query($con, $studentSearch);
        $studentSearchQueryNum = mysqli_num_rows($studentSearchQuery);
         

    }
    else{
        header("Refresh:0; MessengerTeacher.php");
    }
?>
<html>
    <head>
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
        <section id = "result" class = "centerPosClass">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Messages you have recieved from <?php echo $firstNameInput ?> <?php echo $lastNameInput?></p>
            <form action="MessengerTeacher.php" method="post">
                <br><br><button class = "button expandButton">Return</button>
            </form>
            <?php

                if($studentSearchQueryNum >= 1){
                    while($rowSeachQueryGrab = $studentSearchQuery->fetch_assoc()): ?> 
                        <?php
                            $studentPickedID = $rowSeachQueryGrab["student_id"];
                            $messageHistoryFind = "select * from messages where Teacher_ID = '$ID' && Student_ID = $studentPickedID && (Question_Answered = 1 || Question_Answered = 2)";
                            $resultMessageHistoryFind = mysqli_query($con, $messageHistoryFind);
                            $numMessageHistoryResult = mysqli_num_rows($resultMessageHistoryFind);
                            if($numMessageHistoryResult >= 1){
                                while($messageRow = $resultMessageHistoryFind->fetch_assoc()): ?> 
                                    <?php
                                        $questionTitle = $messageRow['Question_Title'];
                                        $questionDescription = $messageRow['Question_Description'];
                                        $questionAnswer = $messageRow['Question_Answer'];
                                        echo'<br><br><section class = "classOutliner">';
                                            echo'<p class = "displayInline"><b>Question Title: </b></p> <p class = "displayInline">'.$questionTitle.'</p><br> ';
                                            echo'<p class = "displayInline"><b>Question Description: </b></p> <p class = "displayInline">'.$questionDescription.'</p><br> ';
                                            echo'<p class = "displayInline"><b>Question Answer: </b></p> <p class = "displayInline">'.$questionAnswer.'</p><br> ';
                                        echo'</section>'
                                    ?>
                            <?php endwhile; 
                            }
                            else{
                                echo'<p>You have not had any question with this student</p>';
                            }



                        ?>
                    <?php endwhile; 
                }
                else{
                    echo '<p>The student you selected does not exist on this system</p>';
                }
            ?>
            </section>
        </section>
    </main>

</html>